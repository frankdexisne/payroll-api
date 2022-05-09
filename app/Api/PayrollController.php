<?php

namespace App\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cutoff;
use Illuminate\Support\Facades\Artisan;

class PayrollController extends Controller
{
    public function processPayroll(Cutoff $cutoff)
    {
        if ($cutoff->status == 2) {
            return redirect('/api/payroll-summary/' . $cutoff->id);
        }
        $cutoff->update(['status' => 1]);
        $cutoffId = $cutoff->id;
        Artisan::call('payroll:compute ' . $cutoff->id);
        return view('processing', compact('cutoffId'));
    }

    public function checkStatus(Request $request, Cutoff $cutoff)
    {
        if ($cutoff->status == 2) {
            return response(null, 200);
        }
        return response(null, 422);
    }
}
