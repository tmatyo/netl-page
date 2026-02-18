<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ExpiredAndNew
{
    private const MYSQL_INSERT_LIMIT = 65535;
    private const COLUMNS = 3;

    public function saveExpiredDomains(array $domains, string $tableName = 'expired_domains'): void
    {
        $this->saveDomains($domains, $tableName);
    }

    public function saveNewDomains(array $domains, string $tableName = 'new_domains'): void
    {
        $this->saveDomains($domains, $tableName);
    }

    private function saveDomains(array $domains, string $tableName): void
    {
        # prepare loop variables
        $domainCount = count($domains);

        if ($domainCount === 0) {
            Log::error("EXPIREDANDNEW: No domains to process for table {$tableName}.");
            return;
        }

        $limit = round(self::MYSQL_INSERT_LIMIT / self::COLUMNS);
        $now = now();

        # loop through domains
        for ($i = 0; $i < $domainCount; $i++) {
            $parsedValues[] = sprintf(
                '("%1$s", "%2$s", "%3$s")',
                $domains[$i] ?? null,
                $now,
                $now
            );

            # insert in chunks
            if (count($parsedValues) == $limit || ($i + 1) == $domainCount) {
                try {
                    $values = implode(",", $parsedValues);
                    DB::statement("INSERT INTO {$tableName} (domain, created_at, updated_at) VALUES {$values}");
                    $parsedValues = [];
                } catch (Exception $e) {
                    Log::error("EXPIREDANDNEW: Error inserting domains into {$tableName}. " . $e->getMessage());
                }
            }
        }
    }
}
