<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = ['invoice_id', 'description', 'quantity', 'unit_price', 'discount_amount', 'tax_amount', 'total_amount'];

    protected function casts(): array
    {
        return ['invoice_id' => 'integer', 'quantity' => 'decimal:2', 'unit_price' => 'decimal:2', 'discount_amount' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total_amount' => 'decimal:2'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
