<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ExpiringDomains
{
    private const MYSQL_INSERT_LIMIT = 65535;
    private const COLUMNS = 11;

    public function saveExpiringDomains(array $domains, string $tableName = 'expiring_domains'): void
    {
        # prepare loop variables
        $domainCount = count($domains);

        if ($domainCount === 0) {
            Log::error("EXPIRINGDOMAINS: No domains to process.");
            return;
        }

        $limit = round(self::MYSQL_INSERT_LIMIT / self::COLUMNS);
        $now = now();

        # loop through expiring domains
        for ($i = 0; $i < $domainCount; $i++) {
            $parsedValues[] = sprintf(
                '("%1$s", "%2$s", "%3$s", "%4$s")',
                $domains[$i]['domain'] ?? null,
                $domains[$i]['expiry_date'] ?? null,
                $now,
                $now
            );

            # insert in chunks
            if (count($parsedValues) == $limit || ($i + 1) == $domainCount) {
                try {
                    $values = implode(",", $parsedValues);
                    DB::statement("INSERT INTO {$tableName} (domain, expiry_date, created_at, updated_at) VALUES {$values}");
                    $parsedValues = [];
                } catch (Exception $e) {
                    Log::error("EXPIRINGDOMAINS: Error inserting expiring domains. " . $e->getMessage());
                }
            }
        }
    }
}
