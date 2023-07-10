<?php

namespace App\Jobs;

use App\Models\Truffle;
use App\Services\TruffleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TruffleFtpExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Truffle  */
    public $truffle;

    /** @var TruffleService */
    private $service;

    public function __construct(Truffle $truffle)
    {
        $this->truffle = $truffle;
        $this->service = app()->make(TruffleService::class);
    }

    public function handle()
    {
        try {
            $this->service->writeTruffleToCsvExportFile($this->truffle);
        } catch (\Throwable $e) {
            Log::channel('job_error')->error('Export failed!', ['exception' => $e]);
        }
    }
}
