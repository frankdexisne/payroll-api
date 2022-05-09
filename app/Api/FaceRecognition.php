<?php

namespace App\Api;

use App\Http\Controllers\Controller;
use App\Models\Attend;
use App\Models\Employee;
use App\Models\EmployeeLeaveAvailment;
use App\Models\Shift;
use App\Events\EmployeePunchIn;
use App\Events\EmployeePunchOut;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FaceRecognition extends Controller
{
    public function findByEmployeeNo($employeeNo): Employee
    {
        return Employee::where('employee_no', $employeeNo)->first();
    }

    public function captureLogs($employeeNo, $action)
    {
        $employee = Employee::where('employee_no', $employeeNo)->first();
        $shift = Shift::where('shift', $this->currentShift($action))->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 422);
        }

        if ($this->isOnLeave($employee)) {
            return response()->json(['message' => 'You are currently on leave'], 422);
        }

        if (!$shift) {
            return response()->json(['message' => 'Invalid action'], 422);
        }

        $currentDate = date('Y-m-d', strtotime(now()));
        $currentTime = date('H:i:s', strtotime(now()));
        $shiftTimeIn = Carbon::parse($currentDate . ' ' . $shift->start);
        $shiftTimeOut = Carbon::parse($currentDate . ' ' . $shift->end);

        if (in_array($action, ['ot-in', 'ot-out'])) {
            $overtime = Overtime::where('employee_id', $employee->id)
                ->where('overtime_date', $currentDate)
                ->where('is_approve', 1)
                ->first();
            if ($overtime) {
                $attend = Attend::where('attend_date', $currentDate)
                    ->where('employee_id', $employee->id)
                    ->where('shift_id', $shift->id)
                    ->first();
                if ($attend) {
                    $attendIn = Carbon::parse($attend->attend_in);
                    $attendOut = Carbon::parse($currentTime);
                    $renderHrs = ($attendOut->diffInMinutes($attendIn) / 60);
                    $acceptedHrs = $renderHrs > $overtime->rendered_hours ? $overtime->rendered_hours : $renderHrs;
                    $attend->update([
                        'attend_out' => date('H:i:s', strtotime($currentTime)),
                        'render_hours' => $renderHrs,
                        'accepted_hours' => $acceptedHrs
                    ]);
                    return response()->json(['message' => 'Successfully OT out'], 200);
                } else {
                    $newAttend = Attend::create([
                        'employee_id' => $employee->id,
                        'shift_id' => $shift->id,
                        'attend_date' => $currentDate,
                        'attend_in' => $currentTime,
                    ]);
                    return response()->json(['message' => 'Successfully OT in'], 200);
                }
            } else {
                return response()->json([
                    'message' => 'You dont have approved overtime'
                ], 422);
            }
        }

        if (in_array($action, ['am-in', 'pm-in'])) {
            $hasAttendThisDay = Attend::where('attend_date', $currentDate)
                ->where('employee_id', $employee->id)
                ->where('shift_id', $shift->id)
                ->first();
            if (!$hasAttendThisDay) {
                $newAttend = Attend::create([
                    'employee_id' => $employee->id,
                    'shift_id' => $shift->id,
                    'attend_date' => $currentDate,
                    'attend_in' => $currentTime,
                ]);
                event(new EmployeePunchIn($newAttend));
                $in = $action == 'am-in' ? 'AM in' : 'PM in';
                return response()->json(['message' => 'Successfully ' . $in], 200);
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
            $attend = Attend::where('attend_date', $currentDate)
                ->where('employee_id', $employee->id)
                ->where('shift_id', $shift->id)
                ->whereNull('attend_out')
                ->first();
            if ($attend) {
                $attendIn = Carbon::parse($attend->attend_date . ' ' . $attend->attend_in);
                $attendOut = Carbon::parse($currentTime);
                $renderHrs = ($attendOut->diffInMinutes($attendIn) / 60);
                $attend->update([
                    'attend_out' => date('H:i:s', strtotime($currentTime)),
                    'render_hours' => $renderHrs,
                    'accepted_hours' => $renderHrs
                ]);
                event(new EmployeePunchOut($attend));
                $out = $action == 'am-in' ? 'AM out' : 'PM out';
                return response()->json(['message' => 'Successfully ' . $out], 200);
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

    public function isOnLeave(Employee $employee)
    {
        $currentDate = date('Y-m-d', strtotime(now()));
        return EmployeeLeaveAvailment::where('year', date('Y', strtotime(now())))
            ->where('is_approve', 1)
            ->where('effective_date_from', '>=', $currentDate)
            ->where('effective_date_to', '<=', $currentDate)
            ->whereHas('employeeLeave', function ($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })
            ->first();
    }
}
