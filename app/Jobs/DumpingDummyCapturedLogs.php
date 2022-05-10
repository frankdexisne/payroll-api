<?php

namespace App\Jobs;

use App\Models\Attend;
use App\Models\Shift;
use App\Models\Overtime;
use App\Models\Employee;
use Carbon\Carbon;
use App\Events\EmployeePunchIn;
use App\Events\EmployeePunchOut;
use Illuminate\Support\Facades\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DumpingDummyCapturedLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataSet;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $cutoff)
    {
        $dataSets = [
            Config::get('cutoff_first_april'),
            Config::get('cutoff_second_april')
        ];
        $this->dataSet = $dataSets[$cutoff];
        $this->queue = 'dump_attendance';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [];
        foreach ($this->dataSet as $attend_date => $data) {
            foreach ($data as $action => $logData) {
                foreach ($logData as $log) {
                    $this->captureLogsDump($log['employee_no'], $action, $attend_date, $log['time']);
                }
            }
        }
    }

    public function findByEmployeeNo($employeeNo): Employee
    {
        return Employee::where('employee_no', $employeeNo)->first();
    }

    public function currentShift($action): string
    {
        $shift = '';
        if (in_array($action, ['am-in', 'am-out'])) {
            $shift = 'am';
        }
        if (in_array($action, ['pm-in', 'pm-out'])) {
            $shift = 'pm';
        }
        if (in_array($action, ['ot-in', 'ot-out'])) {
            $shift = 'ot';
        }
        return $shift;
    }

    public function captureLogsDump($employeeNo, $action, $date, $time)
    {
        $employee = Employee::where('employee_no', $employeeNo)->first();
        $shift = Shift::where('shift', $this->currentShift($action))->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 422);
        }

        if (!$shift) {
            return response()->json(['message' => 'Invalid action'], 422);
        }

        // $date = date('Y-m-d', strtotime($date));
        // $time = date('H:i:s', strtotime($time));
        $shiftTimeIn = Carbon::parse($date . ' ' . $shift->start);
        $shiftTimeOut = Carbon::parse($shift->end);

        if (in_array($action, ['ot-in', 'ot-out'])) {
            $overtime = Overtime::where('employee_id', $employee->id)
                ->where('overtime_date', $date)
                ->where('is_approve', 1)
                ->first();
            if ($overtime) {
                $attend = Attend::where('attend_date', $date)
                    ->where('employee_id', $employee->id)
                    ->where('shift_id', $shift->id)
                    ->first();
                if ($attend) {
                    $attendIn = Carbon::parse($attend->attend_in);
                    $attendOut = Carbon::parse($time);
                    $renderHrs = ($attendOut->diffInMinutes($attendIn) / 60);
                    $acceptedHrs = $renderHrs > $overtime->rendered_hours ? $overtime->rendered_hours : $renderHrs;
                    $attend->update([
                        'attend_out' => date('H:i:s', strtotime($time)),
                        'render_hours' => $renderHrs,
                        'accepted_hours' => $acceptedHrs
                    ]);
                } else {
                    $newAttend = Attend::create([
                        'employee_id' => $employee->id,
                        'shift_id' => $shift->id,
                        'attend_date' => $date,
                        'attend_in' => $time,
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'You dont have approved overtime'
                ], 422);
            }
        }

        if (in_array($action, ['am-in', 'pm-in'])) {
            $hasAttendThisDay = Attend::where('attend_date', $date)
                ->where('employee_id', $employee->id)
                ->where('shift_id', $shift->id)
                ->first();
            if (!$hasAttendThisDay) {
                $newAttend = Attend::create([
                    'employee_id' => $employee->id,
                    'shift_id' => $shift->id,
                    'attend_date' => $date,
                    'attend_in' => $time,
                ]);
                event(new EmployeePunchIn($newAttend, $date));
            } else {
                if ($shift->id == 1) {
                    $message = 'Invalid action, your next attempt will be AM time-out';
                } else {
                    $message = 'Invalid action, your next attempt will be PM time-out';
                }
                return response()->json([
                    'message' => $message
                ], 422);
            }
        }

        if (in_array($action, ['am-out', 'pm-out'])) {
            $attend = Attend::where('attend_date', $date)
                ->where('employee_id', $employee->id)
                ->where('shift_id', $shift->id)
                ->whereNull('attend_out')
                ->first();
            if ($attend) {
                $attendIn = Carbon::parse($attend->attend_date . ' ' . $attend->attend_in);
                $attendOut = Carbon::parse($time);
                $renderHrs = ($attendOut->diffInMinutes($attendIn) / 60);
                $attend->update([
                    'attend_out' => date('H:i:s', strtotime($time)),
                    'render_hours' => $renderHrs,
                    'accepted_hours' => $renderHrs
                ]);
                event(new EmployeePunchOut($attend, $date));
            } else {
                if ($shift->id == 1) {
                    $message = 'Invalid action, you dont have AM time-in';
                } else {
                    $message = 'Invalid action, you dont have PM time-in';
                }
                return response()->json([
                    'message' => $message
                ], 422);
            }
        }
    }
}
