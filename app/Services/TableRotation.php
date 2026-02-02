<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;
use Throwable;

class TableRotation
{

    protected const BACKUP_SUFFIX = "_backup";
    protected const TEMP_SUFFIX = "_temp";

    public function swapTables(): bool
    {
        $tables = config('database.tables_to_rotate');
        foreach ($tables as $table) {
            try {
                if (!$tables || !$table) {
                    throw new RuntimeException("Table name or tablest list does not exist.");
                }

                if (!Schema::hasTable($table)) {
                    throw new RuntimeException("Table with name " . $table . " does not exist.");
                }

                Schema::dropIfExists($table . self::BACKUP_SUFFIX);
                $statement = self::prepareRenameTableQuery($table, $table . self::BACKUP_SUFFIX);
                DB::statement("RENAME TABLE " . $statement . ";");
            } catch (Throwable $ex) {
                Log::error('dropBackupTable(' . $table . ') failed: ', [
                    'exception' => $ex
                ]);
                return false;
            }
        }

        return self::runMigrations();
    }

    public function runMigrations(): bool
    {
        $migrations = config('database.migrations_to_rotate');
        DB::table('migrations')->whereIn('migration', $migrations)->delete();
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        $commandOutput = Artisan::output();
        Log::info('runMigrations: (exit code: ' . $exitCode . ').', [
            'exception' => $commandOutput
        ]);
        return $exitCode === 0;
    }

    public function reverseTableSwap()
    {
        $tables = config('database.tables_to_rotate') ?? [];
        $lastKey = array_key_last($tables);
        $tabletoTemp = '';
        $oldToTable = '';
        $tempToOld = '';

        foreach ($tables as $key => $table) {
            try {
                if (!$tables || !$table) {
                    throw new RuntimeException("Table name or tablest list does not exist.");
                }

                if (!Schema::hasTable($table)) {
                    throw new RuntimeException("Table with name " . $table . " does not exist.");
                }

                $isLastItem = $key === $lastKey;
                $old = $table . self::BACKUP_SUFFIX;
                $temp = $table . self::TEMP_SUFFIX;

                if (!Schema::hasTable($old)) {
                    throw new RuntimeException("Table with name " . $old . " does not exist.");
                }

                $tabletoTemp .= self::prepareRenameTableQuery($table, $temp) . $isLastItem ? ';' : ', ';
                $oldToTable .= self::prepareRenameTableQuery($old, $table) . $isLastItem ? ';' : ', ';
                $tempToOld .= self::prepareRenameTableQuery($temp, $old) . $isLastItem ? ';' : ', ';
            } catch (Throwable $ex) {
                Log::error('reverseTableSwap validation failed: ', [
                    'exception' => $ex
                ]);
            }
        }

        DB::transaction(function () use ($tabletoTemp, $oldToTable, $tempToOld) {
            try {
                DB::statement("RENAME TABLE " . $tabletoTemp);
                DB::statement("RENAME TABLE " . $oldToTable);
                DB::statement("RENAME TABLE " . $tempToOld);
            } catch (Throwable $ex) {
                Log::error('reverseTableSwap renaming failed: ', [
                    'exception' => $ex
                ]);
            }
        });
    }

    private static function prepareRenameTableQuery(string $from, string $to): string
    {
        $from = str_replace('`', '``', $from);
        $to = str_replace('`', '``', $to);
        return "`" . $from . "` TO `" . $to . "`";
    }
}
