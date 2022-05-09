<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEmployeeThatYearInService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->queue = 'year_in_service';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employees = Employee::whereRaw('date_hired < DATE_SUB(NOW(), INTERVAL 1 YEAR)')
            ->whereNotNull('date_resigned')
            ->whereDoesntHave('employeeLeave')
            ->get()
            ->all();

        foreach ($employees as $employee) {
            $leaves = Leave::whereIn('gender_id', [null, $employee->gender_id])->get();
            foreach ($leaves as $leave) {
                $employee->employeeLeave()->create([
                    'leave_id' => $leave->id
                ]);
            }
        }
    }
}
