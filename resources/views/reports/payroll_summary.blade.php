<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payroll Summary - {{ $payrollPeriod }}</title>
    <style>
        table#summary tr th,
        td {
            font-size: 10px;
            font-family: 'Courier New', Courier, monospace';

        }

        table#summary {
            margin-top: 15px;
        }

    </style>
</head>

<body>
    <table>
        <tr>
            <td width="60px">
                <img src="{{ asset('/') }}/images/Ako_Bicol_logo.png" width="50px" height="50px" />
            </td>
            <td width="930px">
                <br><br>
                <font style="font-size: 15px; font-weight: bold;">Payroll Summary</font>
                <br>
                as of {{ $payrollPeriod }}
            </td>
        </tr>
    </table>
    <br><br>
    <table id="summary" border="0.5">
        <tr>
            <th width="150px" align="center">EMPLOYEE</th>
            <th width="30px" align="center">DAYS</th>
            <th width="70px" align="center">BASIC</th>
            <th width="60px" align="center">OVERTIME</th>
            <th width="60px" align="center">HOLIDAY</th>
            <th width="45px" align="center">(ADD)</th>
            <th width="45px" align="center">SSS</th>
            <th width="45px" align="center">HDMF</th>
            <th width="45px" align="center">Phil</th>
            <th width="45px" align="center">Tax</th>
            <th width="45px" align="center">Late</th>
            <th width="45px" align="center">Utime</th>
            <th width="60px" align="center">SSS Loan</th>
            <th width="60px" align="center">HDMF Loan</th>
            <th width="50px" align="center">(LESS)</th>
            <th width="80px" align="center">Net</th>
            <th width="60px" align="center">View</th>
        </tr>
        @foreach ($cutoff->payslip as $payslip)
            <tr>
                <td>{{ $payslip->employee->full_name }}</td>
                <td>{{ $payslip->no_of_days }}</td>
                <td align="right">{{ $payslip->basic_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->overtime_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->holiday_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->adjustment_add_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->sss_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->hdmf_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->philhealth_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->tax_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->late_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->undertime_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->sss_loan_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->pagibig_loan_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->adjustment_less_amount }}&nbsp;&nbsp;</td>
                <td align="right">{{ $payslip->net_amount }}&nbsp;&nbsp;</td>
                <td align="center"><a href="{{ url('/api/payslip/' . $payslip->id) }}" target="_blank">View</a></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="2"><b>TOTALS</b></td>
            <td align="right">{{ number_format($cutoff->payslip->sum('basic'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('overtime'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('holiday'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('adjustment_add'), 2, '.', ',') }}&nbsp;&nbsp;
            </td>
            <td align="right">{{ number_format($cutoff->payslip->sum('sss'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('pagibig'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('philhealth'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('tax'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('late'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('undertime'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('sss_loan'), 2, '.', ',') }}&nbsp;&nbsp;</td>
            <td align="right">{{ number_format($cutoff->payslip->sum('pagibig_loan'), 2, '.', ',') }}&nbsp;&nbsp;
            </td>
            <td align="right">{{ number_format($cutoff->payslip->sum('adjustment_less'), 2, '.', ',') }}&nbsp;&nbsp;
            </td>
            <td align="right">
                {{ number_format($cutoff->payslip->sum('gross') - ($cutoff->payslip->sum('sss') + $cutoff->payslip->sum('pagibig') + $cutoff->payslip->sum('philhealth') + $cutoff->payslip->sum('tax') + $cutoff->payslip->sum('late') + $cutoff->payslip->sum('undertime') + $cutoff->payslip->sum('adjustment_less')), 2, '.', ',') }}&nbsp;&nbsp;
            </td>
            <td></td>
        </tr>
    </table>
</body>

</html>
