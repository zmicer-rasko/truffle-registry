<?php

namespace App\Services;

use App\DTO\TruffleCsvDTO;
use App\Jobs\TruffleFtpExport;
use App\Models\Truffle;
use App\Models\User;
use App\Validation\CreateTruffle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DateTime;

class TruffleService
{
    public const REDIS_KEY = 'truffles';

    public const EXPORT_FILE = 'export.csv';
    public const IMPORT_FILE = 'import.csv';

    public const TRUFFLE_EXPIRATION = '1 month';

    /**
     * @param array $data
     * @param false $isImport
     * @return Truffle
     */
    public function saveTruffle(array $data, $isImport = false)
    {
        $now = new DateTime();

        // Save truffle
        $truffle = new Truffle();
        $truffle->sku = $isImport ? $data['sku'] : Truffle::generateSku();
        $truffle->weight = $data['weight'];
        $truffle->price = $data['price'];
        $truffle->created_at = $now;
        $truffle->expires_at = $now->modify('+' . self::TRUFFLE_EXPIRATION);
        $truffle->user_id = $isImport ? null : Auth::user()->id;
        $truffle->source_type = $isImport ? User::ROLE_MANUFACTURER : User::ROLE_HUNTER;
        $truffle->save();

        // Write it to the export CSV
        TruffleFtpExport::dispatch($truffle);

        return $truffle;
    }

    public function importTrufflesFromCsv()
    {
        $path = Storage::path(self::IMPORT_FILE);
        $input = fopen($path, 'r');

        while ($row = fgetcsv($input, 1000)) {

            $payload = TruffleCsvDTO::createFromCsvRow($row)->getArray();
            $validator = Validator::make($payload, CreateTruffle::getRules());

            if (!$validator->fails()) {
                $this->saveTruffle($payload, true);
            }
        }

        fclose($input);
    }

    /**
     * @param Truffle $truffle
     */
    public function writeTruffleToCsvExportFile(Truffle $truffle)
    {
        if ($this->isAlreadyProcessed($truffle->sku)) {
            return;
        }

        $path = self::EXPORT_FILE;

        $row = TruffleCsvDTO::createFromModel($truffle)->getCsvRow();
        if (Storage::exists($path)) {
            Storage::append($path, $row);
        } else {
            Storage::put($path, $row);
        }

        Redis::sadd(self::REDIS_KEY, $truffle->sku);

        $truffle->update(['exported_at' => new \DateTime()]);
    }

    /**
     * @param $sku
     * @return mixed
     */
    private function isAlreadyProcessed($sku)
    {
        return Redis::sismember(self::REDIS_KEY, (string)$sku);
    }
}