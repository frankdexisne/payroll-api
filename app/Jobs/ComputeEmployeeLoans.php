<?php

namespace App\Jobs;

use App\Contributions\EmployeeContribution;
use App\Models\Cutoff;
use App\Models\Payslip;
use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanDeduction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeLoans implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cutoff;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cutoff $cutoff)
    {
        $this->cutoff = $cutoff;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $loans = EmployeeLoan::where('period', $this->cutoff->period)
            ->where('balance', '>', 0)
            ->where('start_at', '>=', $this->cutoff->from)
            ->get();
        foreach ($loans->all() as $loan) {
            $field = $loan->loan_id == 1 ? 'pagibig_loan' : 'sss_loan';
            $payslip = Payslip::where('employee_id', $loan->employee_id)
                ->where('cutoff_id', $this->cutoff->id)
                ->first();
            $payslip->update([
                $field => $loan->amortization
            ]);
            $employeeLoanDeduction = EmployeeLoanDeduction::firstOrNew([
                'payslip_id' => $payslip->id,
                'employee_loan_id' => $loan->id,
            ]);

            if (!$employeeLoanDeduction->exists) {
                $employeeLoanDeduction->fill([
                    'loan_id' => $loan->loan_id,
                    'deduct_at' => date('Y-m-d', strtotime(now())),
                    'amount' => $loan->amortization
                ])
                    ->save();
            }
            $loan->update([
                'balance' => $loan->balance - $loan->amortization,
                'settled' => $loan->settled + $loan->amortization
            ]);
        }
    }
}
