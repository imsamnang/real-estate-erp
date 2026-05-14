<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentSchedule extends Model
{
    protected $table = 'installment_schedules';

    protected $fillable = ['sale_contract_id', 'installment_no', 'due_date', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'paid_amount', 'balance_amount', 'status', 'paid_at'];

    protected function casts(): array
    {
        return ['sale_contract_id' => 'integer', 'installment_no' => 'integer', 'due_date' => 'date', 'principal_amount' => 'decimal:2', 'interest_amount' => 'decimal:2', 'penalty_amount' => 'decimal:2', 'total_amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'balance_amount' => 'decimal:2', 'paid_at' => 'datetime'];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SaleContract::class, 'sale_contract_id');
    }
}
