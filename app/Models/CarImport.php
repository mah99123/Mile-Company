<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_type',
        'lot_number',
        'total_with_transfer',
        'amount_received',
        'remaining_amount',
        'auction_invoice_date',
        'auction_invoice_number',
        'office_contract_number',
        'office_contract_date',
        'office_invoice_amount',
        'company_shipping_cost',
        'customer_shipping_cost',
        'remaining_office_invoice',
        'shipping_profit',
        'office_commission',
        'currency',
        'notes',
        'employee_assigned',
        'shipping_status',
        'shipping_company',
        'buyer_company',
        'pull_date',
        'shipping_date',
        'arrival_date',
        'container_number',
        'recipient_name',
        'recipient_receive_date',
        'recipient_phone',
    ];

    protected $casts = [
        'auction_invoice_date' => 'date',
        'office_contract_date' => 'date',
        'pull_date' => 'date',
        'shipping_date' => 'date',
        'arrival_date' => 'date',
        'recipient_receive_date' => 'date',
        'total_with_transfer' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'office_invoice_amount' => 'decimal:2',
        'company_shipping_cost' => 'decimal:2',
        'customer_shipping_cost' => 'decimal:2',
        'remaining_office_invoice' => 'decimal:2',
        'shipping_profit' => 'decimal:2',
        'office_commission' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_assigned');
    }

    public function getTotalProfitAttribute()
    {
        return $this->shipping_profit + $this->office_commission;
    }
}
