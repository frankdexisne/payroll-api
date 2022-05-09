<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $employeeName }} - {{ $payrollPeriod }}</title>
    <style>
        table#summary tr th,
        td {
            font-size: 11px;
            font-family: 'Courier New', Courier, monospace';

        }

        table#summary tr th {
            font-weight: bold;
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
            <td width="430px">
                <br>
                <font style="font-size: 15px; font-weight: bold;">PAYSLIP</font>
                <br>
                <font style="font-size: 10px; font-weight: bold;">{{ $employeeName }}</font>
                <br>
                As of {{ $payrollPeriod }}
            </td>
        </tr>
    </table>
    <br><br>
    <table id="summary">
        <tr>
            <th width="120px"><b>No of Days</b></th>
            <td width="150px" align="right">0&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th width="120px"><b>Basic</b></th>
            <td width="150px" align="right">{{ number_format($payslip->basic, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Overtime</b></th>
            <td align="right">{{ number_format($payslip->overtime, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Holiday</b></th>
            <td align="right">{{ number_format($payslip->holiday, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <th><b>Gross</b></th>
            <td align="right">{{ number_format($payslip->gross, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <th><b>Late</b></th>
            <td align="right">{{ number_format($payslip->late, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Undertime</b></th>
            <td align="right">{{ number_format($payslip->undertime, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>SSS</b></th>
            <td align="right">{{ number_format($payslip->sss, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>HDMF</b></th>
            <td align="right">{{ number_format($payslip->pagibig, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Philhealth</b></th>
            <td align="right">{{ number_format($payslip->philhealth, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Tax</b></th>
            <td align="right">{{ number_format($payslip->tax, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>SSS Loan</b></th>
            <td align="right">{{ number_format($payslip->sss_loan, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <th><b>Pagibig Loan</b></th>
            <td align="right">{{ number_format($payslip->pagibig_loan, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <th><b>Total Deduction</b></th>
            <td align="right">
                @php
                    $totalDeduction = $payslip->late + $payslip->undertime + $payslip->sss + $payslip->pagibig + $payslip->philhealth + $payslip->tax + $payslip->sss_loan + $payslip->pagibig_loan;
                @endphp
                {{ number_format($totalDeduction, 2, '.', ',') }}&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <th><b>NET PAY</b></th>
            <td align="right" style="font-size: 14px; font-weight: bold;">
                {{ number_format($payslip->gross - $totalDeduction, 2, '.', ',') }}&nbsp;&nbsp;</td>
        </tr>
    </table>
</body>

</html>
