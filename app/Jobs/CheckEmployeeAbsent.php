<?php

namespace App\Jobs;

use App\Models\Cutoff;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Absence;
use App\Models\Attend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEmployeeAbsent implements ShouldQueue
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
        $date = date('Y-m-d', strtotime($this->cutoff->from));

        while ($date <= date('Y-m-d', strtotime($this->cutoff->to))) {
            $day = date('D', strtotime($date));
            if (in_array($day, $this->weekDays()) && !in_array($date, $this->holidays())) {
                $employees = Employee::whereNull('date_resigned')
                    ->get()
                    ->all();
                foreach ($employees as $employee) {
                    $shiftAttend = Attend::where('employee_id', $employee->id)
                        ->where('attend_date', $date)
                        ->whereIn('shift_id', [1, 2])
                        ->count();
                    if ($shiftAttend < 2) {
                        $hoursCommited = $shiftAttend == 0 ? 8 : 4;
                        $absent = Absence::firstOrNew([
                            'employee_id' => $employee->id,
                            'date_commited' => $date,
                        ]);
                        if (!$absent->exists) {
                            $absent->fill([
                                'hours_commited' => $hoursCommited
                            ])
                                ->save();
                        }
                    }
                }
            }
            $date = date('Y-m-d', strtotime($date . '+ 1 days'));
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
