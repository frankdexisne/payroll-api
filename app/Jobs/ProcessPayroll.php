<?php

namespace App\Jobs;

use App\Models\Cutoff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ProcessPayroll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cutoff;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cutoff $cutoff)
    {
        $this->cutoff = $cutoff;
        $this->queue = 'process_payroll';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->cutoff->status !== 2) {
            Bus::chain([
                new ComputeEmployeeBasic($this->cutoff),
                new CheckEmployeeAbsent($this->cutoff),
                new ComputeEmployeeNumberOfDays($this->cutoff),
                new ComputeEmployeeOvertimePay($this->cutoff),
                new ComputeEmployeeHolidayPay($this->cutoff),
                new ComputeEmployeeLeaves($this->cutoff),
                new ComputeEmployeeGrossPay($this->cutoff),
                new ComputeEmployeeLates(($this->cutoff)),
                new ComputeEmployeeUndertimes($this->cutoff),
                new ComputeEmployeeLoans($this->cutoff),
                new ComputeEmployeeContributions($this->cutoff)
            ])
                ->onConnection('redis')
                ->onQueue('process_payroll')
                ->dispatch();
        }
    }
}
