<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'invoice_id',
        'payment_date',
        'due_date',
        'amount_paid',
        'status',
        'receipt_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'due_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'invoice_id');
    }

    /**
     * تحديد ما إذا كان القسط متأخر
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    /**
     * الحصول على الأقساط المتأخرة
     */
    public static function overdue()
    {
        return static::where('status', 'pending')
            ->where('due_date', '<', now());
    }

    /**
     * الحصول على الأقساط المستحقة اليوم
     */
    public static function dueToday()
    {
        return static::where('status', 'pending')
            ->whereDate('due_date', today());
    }
}
