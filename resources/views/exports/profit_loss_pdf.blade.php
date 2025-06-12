<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الأرباح والخسائر</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin-top: 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .profit {
            color: green;
        }
        .loss {
            color: red;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f2f2f2;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الأرباح والخسائر</h1>
        <p>
            الفترة: 
            @if($data['month'])
                {{ date('F', mktime(0, 0, 0, $data['month'], 1)) }} {{ $data['year'] }}
            @else
                السنة المالية {{ $data['year'] }}
            @endif
        </p>
        <p>تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
    </div>

    <div class="section">
        <h2>ملخص الأرباح والخسائر</h2>
        <table>
            <tr>
                <th>البند</th>
                <th>القيمة (دينار)</th>
            </tr>
            <tr>
                <td>إجمالي الإيرادات</td>
                <td>{{ number_format($data['revenue'], 2) }}</td>
            </tr>
            <tr>
                <td>إجمالي التكاليف</td>
                <td>{{ number_format($data['costs'], 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>صافي الربح / الخسارة</td>
                <td class="{{ $data['profit'] >= 0 ? 'profit' : 'loss' }}">
                    {{ number_format($data['profit'], 2) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <h3>تحليل الأداء المالي</h3>
        <p>
            @if($data['profit'] > 0)
                حققت الشركة ربحًا صافيًا قدره {{ number_format($data['profit'], 2) }} دينار خلال هذه الفترة.
            @elseif($data['profit'] < 0)
                تكبدت الشركة خسارة صافية قدرها {{ number_format(abs($data['profit']), 2) }} دينار خلال هذه الفترة.
            @else
                حققت الشركة نقطة التعادل خلال هذه الفترة، حيث تساوت الإيرادات مع التكاليف.
            @endif
        </p>
        <p>
            نسبة هامش الربح: 
            @if($data['revenue'] > 0)
                {{ number_format(($data['profit'] / $data['revenue']) * 100, 2) }}%
            @else
                0%
            @endif
        </p>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام إدارة المبيعات - جميع الحقوق محفوظة &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
