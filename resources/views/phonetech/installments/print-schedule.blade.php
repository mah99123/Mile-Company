<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جدول الأقساط - {{ $sale->invoice_id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            text-align: center;
        }
        .summary-box {
            width: 24%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .summary-box h4 {
            margin: 0 0 5px 0;
        }
        .summary-box p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .paid {
            background-color: #d4edda;
        }
        .overdue {
            background-color: #f8d7da;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #333;
            padding-top: 5px;
            text-align: center;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="/logo.png" alt="شعار الشركة" class="logo">
            <h1>جدول الأقساط</h1>
            <p>محمد فون تك - بيع وصيانة الهواتف والأجهزة الإلكترونية</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>معلومات العميل</h3>
                <p><strong>الاسم:</strong> {{ $sale->customer_name }}</p>
                <p><strong>الهاتف:</strong> {{ $sale->customer_phone }}</p>
                @if($sale->customer_address)
                <p><strong>العنوان:</strong> {{ $sale->customer_address }}</p>
                @endif
                @if($sale->customer_id_number)
                <p><strong>رقم الهوية:</strong> {{ $sale->customer_id_number }}</p>
                @endif
            </div>
            <div class="info-box">
                <h3>معلومات الفاتورة</h3>
                <p><strong>رقم الفاتورة:</strong> {{ $sale->invoice_id }}</p>
                <p><strong>تاريخ البيع:</strong> {{ $sale->sale_date->format('Y-m-d') }}</p>
                <p><strong>المنتج:</strong> {{ $sale->product->name ?? 'منتج محذوف' }}</p>
                <p><strong>الحالة:</strong> 
                    @switch($sale->status)
                        @case('Pending') في الانتظار @break
                        @case('Active') نشط @break
                        @case('Overdue') متأخر @break
                        @case('Completed') مكتمل @break
                    @endswitch
                </p>
            </div>
        </div>

        <div class="summary-section">
            <div class="summary-box">
                <h4>{{ number_format($sale->total_amount, 2) }}</h4>
                <p>المبلغ الإجمالي (دينار)</p>
            </div>
            <div class="summary-box">
                <h4>{{ number_format($sale->down_payment, 2) }}</h4>
                <p>الدفعة الأولى (دينار)</p>
            </div>
            <div class="summary-box">
                <h4>{{ $sale->installment_period_months }}</h4>
                <p>فترة التقسيط (شهر)</p>
            </div>
            <div class="summary-box">
                <h4>{{ number_format($sale->monthly_installment, 2) }}</h4>
                <p>القسط الشهري (دينار)</p>
            </div>
        </div>

        <h3>جدول الأقساط</h3>
        <table>
            <thead>
                <tr>
                    <th>رقم القسط</th>
                    <th>تاريخ الاستحقاق</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>تاريخ الدفع</th>
                    <th>المبلغ المدفوع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedule as $installment)
                <tr class="{{ $installment['status'] == 'Paid' ? 'paid' : ($installment['status'] == 'Overdue' ? 'overdue' : '') }}">
                    <td>{{ $installment['installment_number'] }}</td>
                    <td>{{ $installment['due_date'] }}</td>
                    <td>{{ number_format($installment['amount'], 2) }} دينار</td>
                    <td>
                        @switch($installment['status'])
                            @case('Paid') مدفوع @break
                            @case('Overdue') متأخر @break
                            @default في الانتظار @break
                        @endswitch
                    </td>
                    <td>{{ $installment['payment_date'] ?: '-' }}</td>
                    <td>{{ $installment['payment_amount'] ? number_format($installment['payment_amount'], 2) . ' دينار' : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>الإجمالي</strong></td>
                    <td><strong>{{ number_format($sale->monthly_installment * $sale->installment_period_months, 2) }} دينار</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <p>توقيع العميل</p>
            </div>
            <div class="signature-box">
                <p>توقيع الشركة</p>
            </div>
        </div>

        <div class="footer">
            <p>محمد فون تك - العنوان: بغداد، العراق - هاتف: 07XXXXXXXXX</p>
            <p>تم إصدار هذا الجدول بتاريخ: {{ date('Y-m-d') }}</p>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print();" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                طباعة الجدول
            </button>
        </div>
    </div>
</body>
</html>
