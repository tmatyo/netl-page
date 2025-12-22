<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Exception;

class DomainSk
{
    private const MYSQL_INSERT_LIMIT = 65535;
    private const COLUMNS = 11;

    public function getDomain($domain)
    {
        return $domain;
    }

    public function saveDomains(array $domains, string $tableName = 'domains'): void
    {

        # prepare loop variables
        $domainCount = count($domains);

        if ($domainCount === 0) {
            return;
        }

        # determine the type of the first element
        $type = gettype($domains[0] ?? null);

        # process data based on type
        if ($type === 'array') {
            $this->saveDomainsLineTypeArray($domains, $tableName, $domainCount);
        } elseif ($type === 'string') {
            $this->saveDomainsLineTypeString($domains, $tableName, $domainCount);
        }
    }

    private function saveDomainsLineTypeString(array $domains, string $tableName, int $domainCount): void
    {
        $limit = round(self::MYSQL_INSERT_LIMIT / self::COLUMNS);
        $now = now();

        # loop through ~40MB of data
        for ($i = 0; $i < $domainCount; $i++) {
            $values = explode(";", $domains[$i]);
            $nameServerValues = explode(",", $values[3] ?? "");
            $parsedValues[] = sprintf(
                '("%1$s", "%2$s", "%3$s", "%4$s", "%5$s", "%6$s", "%7$s", "%8$s", "%9$s", "%10$s")',
                $values[0] ?? null,
                $values[1] ?? null,
                $values[2] ?? null,
                $nameServerValues[0] ?? null,
                $nameServerValues[1] ?? null,
                $nameServerValues[2] ?? null,
                $nameServerValues[3] ?? null,
                $values[4] ?? null,
                $now,
                $now
            );

            # save processed data in chunks or when the loop is done
            if (count($parsedValues) == $limit || ($i + 1) == $domainCount) {
                try {
                    DB::statement('INSERT INTO `' . $tableName . '` (domain, id_reg, id_owner, ns1, ns2, ns3, ns4, expiry_date, created_at, updated_at) VALUES ' . implode(',', $parsedValues));
                    $parsedValues = [];
                } catch (Exception $ex) {
                    error_log("DOMENASK: Data insertion failed: " . $ex->getMessage());
                    exit;
                }
            }
        }
    }

    private function saveDomainsLineTypeArray(array $domains, string $tableName, int $domainCount): void
    {
        $limit = 1000;

        # loop through ~40MB of data
        for ($i = 0; $i < $domainCount; $i++) {
            $parsedValues[] = $domains[$i];

            # save processed data in chunks or when the loop is done
            if (count($parsedValues) == $limit || ($i + 1) == $domainCount) {
                try {
                    DB::table($tableName)->insert($parsedValues);
                    $parsedValues = [];
                } catch (Exception $ex) {
                    error_log("DOMENASK: Data insertion failed: " . $ex->getMessage());
                    exit;
                }
            }
        }
    }
}
