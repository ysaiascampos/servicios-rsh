<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Clases\GenerarExcelQueue;

class GeneracionExcelComuna implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $com;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($com)
    {
        $this->com = $com;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Log::info("data :" . $this->com );
        GenerarExcelQueue::GenerarExcel($this->com);
    }
}
