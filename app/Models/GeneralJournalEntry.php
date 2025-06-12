<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalEntry extends Model
{
    use HasFactory;

    protected $primaryKey = 'entry_id';

    protected $fillable = [
        'date',
        'description',
        'debit_account',
        'credit_account',
        'amount',
        'reference_number',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function debitAccount()
    {
        return $this->belongsTo(Account::class, 'debit_account', 'account_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(Account::class, 'credit_account', 'account_id');
    }
}
