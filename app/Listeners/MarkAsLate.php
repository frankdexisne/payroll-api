<?php

namespace App\Listeners;

use App\Events\EmployeePunchIn;
use App\Models\Shift;
use App\Models\Tardy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class MarkAsLate
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
    public function handle(EmployeePunchIn $event)
    {
        // $shift = Shift::find($event->attend->shift_id)->first();
        $shift = $event->attend->shift()->first();

        $shiftStart = Carbon::parse($event->attend->attend_date . ' ' . $shift->start);
        $actualTimein = Carbon::parse($event->attend->attend_in);

        if ($actualTimein > $shiftStart) {
            Tardy::create([
                'employee_id' => $event->attend->employee_id,
                'date_commited' => $event->attend->attend_date,
                'minutes_commited' => $actualTimein->diffInMinutes($shiftStart)
            ]);
        }
    }
}
