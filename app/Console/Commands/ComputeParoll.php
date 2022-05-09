<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessPayroll;
use App\Models\Cutoff;

class ComputeParoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:compute {cutoffId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Computing payroll';

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
        $cutoff = Cutoff::find($this->argument('cutoffId'));
        $job = ProcessPayroll::dispatch($cutoff);
        redirect('/api/payroll-summary/' . $cutoff->id);
    }
}
