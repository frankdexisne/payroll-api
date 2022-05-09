<?php

namespace App\Listeners;

use App\Models\Shift;
use App\Events\EmployeePunchOut;
use App\Models\Undertime;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkAsUndertime
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EmployeePunchOut $event)
    {
        $shift = $event->attend->shift()->first();

        $endShift = Carbon::parse($event->attend->attend_date . ' ' . $shift->end);
        $actualTimeout = Carbon::parse($event->attend->attend_date . ' ' . $event->attend->attend_out);


        if ($endShift > $actualTimeout) {
            Undertime::create([
                'employee_id' => $event->attend->employee_id,
                'date_commited' => $event->attend->attend_date,
                'minutes_commited' => $endShift->diffInMinutes($actualTimeout)
            ]);
        }
    }
}
