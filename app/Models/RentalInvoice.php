<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalInvoice extends Model
{
    protected $table = 'rental_invoices';

    protected $fillable = ['rental_contract_id', 'invoice_id', 'rent_month', 'due_date', 'rent_amount', 'penalty_amount', 'total_amount', 'status'];

    protected function casts(): array
    {
        return ['rental_contract_id' => 'integer', 'invoice_id' => 'integer', 'due_date' => 'date', 'rent_amount' => 'decimal:2', 'penalty_amount' => 'decimal:2', 'total_amount' => 'decimal:2'];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(RentalContract::class, 'rental_contract_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
