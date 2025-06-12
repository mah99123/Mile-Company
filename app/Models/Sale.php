<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'product_id',
        'sale_date',
        'total_amount',
        'down_payment',
        'remaining_amount',
        'installment_amount',
        'installment_period_months',
        'installments_paid',
        'next_installment_due_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'next_installment_due_date' => 'date',
        'total_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class, 'invoice_id');
    }

    public function getIsOverdueAttribute()
    {
        return $this->next_installment_due_date < now() && $this->status === 'Active';
    }

    public function getRemainingInstallmentsAttribute()
    {
        return $this->installment_period_months - $this->installments_paid;
    }
}
