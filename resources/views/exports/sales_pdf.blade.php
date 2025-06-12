<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المبيعات</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
        <h1>تقرير المبيعات</h1>
        <p>تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الإجمالي</th>
                <th>البائع</th>
                <th>تاريخ البيع</th>
                <th>طريقة الدفع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sale->product->name }}</td>
                <td>{{ number_format($sale->unit_price, 2) }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ number_format($sale->total_price, 2) }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                <td>{{ $sale->payment_method }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>ملخص التقرير</h3>
        <p>إجمالي عدد المبيعات: {{ $sales->count() }}</p>
        <p>إجمالي قيمة المبيعات: {{ number_format($sales->sum('total_price'), 2) }}</p>
        <p>متوسط قيمة المبيعات: {{ number_format($sales->avg('total_price'), 2) }}</p>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام إدارة المبيعات - جميع الحقوق محفوظة &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
