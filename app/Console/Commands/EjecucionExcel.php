<?php

namespace App\Console\Commands;

use App\Jobs\GeneracionExcelComuna;
use App\Models\Comuna;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class EjecucionExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excelcom:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lstComuna = DB::table('comunas_ine')->pluck('COM_DESCRIPCION','COM_ID');
        foreach ($lstComuna as $key =>$valor) {
            GeneracionExcelComuna::dispatch($key);
        }
    }
}
