<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\statesController;

class RunImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to import data';

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
     * @return mixed
     */
    public function handle()
    {       
        $state = new StatesController();
        $state->import();
        $state->importRelationship();

        $import = new ImportsController();
        $response = $import->import();

        echo 'Importação OK';
    }
}
