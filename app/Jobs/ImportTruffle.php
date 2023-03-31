<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ImportTruffle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const REDIS_KEY = 'truffles';
    public const SOURCE_FILE = 'import.csv';

    protected $truffle;

    public function handle()
    {
        $inputPath = storage_path('app') . DIRECTORY_SEPARATOR . self::SOURCE_FILE;
        file_exists(dirname($inputPath)) || mkdir(dirname($inputPath), 0777, true);
        $input = fopen($inputPath, 'a+');

        $outputPath = storage_path('app') . DIRECTORY_SEPARATOR . ProcessTruffle::EXPORT_FILE;
        file_exists(dirname($outputPath)) || mkdir(dirname($outputPath), 0777, true);
        $output = fopen($outputPath, 'a+');

        while ([$sku, $weight, $price, $expiresAt] = fgetcsv($input, 1000)) {
            if (!$this->isAlreadyProcessed($sku)) {
                fputcsv($output, [$sku, $weight, $price, $expiresAt]);
                Redis::sadd(self::REDIS_KEY, $sku);
            }
        }

        fclose($input);
        fclose($output);
    }

    private function isAlreadyProcessed($sku)
    {
        return Redis::sismember(self::REDIS_KEY, (string)$sku);
    }
}
