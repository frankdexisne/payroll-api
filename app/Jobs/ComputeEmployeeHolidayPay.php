<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Payslip;
use App\Models\Holiday;
use App\Computations\HolidayPay;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeHolidayPay implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $holidays = Holiday::whereBetween(
            'holiday_date',
            [
                $this->cutoff->from,
                $this->cutoff->to
            ]
        )
            ->get();

        foreach ($holidays as $holiday) {
            // REGULAR HOLIDAY
            if ($holiday->is_regular) {
                $payslips = Payslip::where('cutoff_id', $this->cutoff->id)
                    ->get()->all();
                foreach ($payslips as $payslip) {
                    $payslip->update([
                        'holiday' => $payslip->holiday + ($payslip->employee->currentRate()->hourly_rate * 8)
                    ]);
                }
            } else {
                $holidayDate = $holiday->holiday_date;
                $employees = Employee::whereHas('attend', function ($query) use ($holidayDate) {
                    $query->where('attend_date', $holidayDate);
                })
                    ->whereNull('date_resigned')
                    ->get();
                foreach ($employees as $employee) {
                    $holidayPay = 0;

                    $holidayPay += (new HolidayPay($holiday))
                        ->computeAmount($employee->id, $employee->currentRate()->hourly_rate);

                    $payslip = Payslip::where('employee_id', $employee->id)
                        ->where('cutoff_id', $this->cutoff->id)
                        ->first();
                    $payslip->update([
                        'holiday' => $payslip->holiday + $holidayPay
                    ]);
                }
            }
        }
    }
}
