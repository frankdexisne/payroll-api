<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Holiday;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ComputeEmployeeNumberOfDays implements ShouldQueue
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
        $totalDays = 0;

        $date = Carbon::parse($this->cutoff->from);
        $dateTo = Carbon::parse($this->cutoff->to);
        while ($date->lte($dateTo)) {
            $day = date('D', strtotime($date));
            $dateYmd = date('Y-m-d', strtotime($date));
            if (in_array($day, $this->weekDays()) && !in_array($dateYmd, $this->holidays())) {
                $totalDays = $totalDays + 1;
            }
            $date->addDays(1);
        }

        $payslips = $this->cutoff->payslip;
        foreach ($payslips as $payslip) {
            $payslip->update([
                'no_of_days' => $totalDays - ($payslip->employee->absence->whereBetween('date_commited', [$this->cutoff->from, $this->cutoff->to])->sum('hours_commited') / 8)
            ]);
        }
    }

    public function weekDays()
    {
        return [
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri'
        ];
    }

    public function holidays()
    {
        return Holiday::whereBetween('holiday_date', [$this->cutoff->from, $this->cutoff->to])
            ->pluck('holiday_date')
            ->toArray();
    }
}
