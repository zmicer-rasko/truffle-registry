<?php

namespace App\Console\Commands;

use App\Services\TruffleService;
use Illuminate\Console\Command;

class TrufflesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truffles:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $service = app()->make(TruffleService::class);
        $service->importTrufflesFromCsv();

        $this->info('Command executed successfully.');
    }
}
