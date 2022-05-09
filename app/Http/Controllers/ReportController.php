<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\Cutoff;
use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function payrollSummary(Cutoff $cutoff)
    {
        $payrollPeriod = $this->payrollPeriod($cutoff);
        $view = View::make('reports.payroll_summary', compact('cutoff', 'payrollPeriod'));
        $html = $view->render();

        TCPDF::SetTitle('Payroll Summary - ' . $cutoff->name);
        TCPDF::SetMargins($left = 0, $top = 5, $right = 0, $keepmargins = false);
        TCPDF::AddPage('L', 'LEGAL');
        TCPDF::writeHTML($html, true, false, true, false, '');
        TCPDF::Output('payroll-summary' . $cutoff->name . '.pdf');
    }

    public function payslip(Payslip $payslip)
    {
        $employeeName = $payslip->employee->lname . ', ' . $payslip->employee->fname . ' ' . $payslip->employee->mname;

        $payrollPeriod = $this->payrollPeriod($payslip->cutoff);
        $view = View::make('reports.payslip', compact('payslip', 'employeeName', 'payrollPeriod'));
        $html = $view->render();

        TCPDF::SetTitle('Payslip-' . $employeeName);
        TCPDF::SetMargins($left = 0, $top = 5, $right = 0, $keepmargins = false);
        TCPDF::AddPage('P', 'A6');
        TCPDF::writeHTML($html, true, false, true, false, '');
        TCPDF::Output('payslip-' . $employeeName . '-' . $payrollPeriod . '.pdf');
    }

    public function payrollPeriod(Cutoff $cutoff)
    {
        $monthIndex = (int)$cutoff->month - 1;
        return $this->getMonthName($monthIndex) . ' ' . $cutoff->payoll_period . ', ' . $cutoff->year;
    }

    public function getMonthName($monthIndex)
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        return $months[$monthIndex];
    }
}
