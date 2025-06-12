<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $primaryKey = 'account_id';

    protected $fillable = [
        'platform',
        'account_name',
        'balance',
        'account_type',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function debitEntries()
    {
        return $this->hasMany(GeneralJournalEntry::class, 'debit_account');
    }

    public function creditEntries()
    {
        return $this->hasMany(GeneralJournalEntry::class, 'credit_account');
    }
}
