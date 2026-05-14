<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleContractItem extends Model
{
    protected $table = 'sale_contract_items';

    protected $fillable = ['sale_contract_id', 'property_id', 'price', 'discount_amount', 'total_amount'];

    protected function casts(): array
    {
        return ['sale_contract_id' => 'integer', 'property_id' => 'integer', 'price' => 'decimal:2', 'discount_amount' => 'decimal:2', 'total_amount' => 'decimal:2'];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SaleContract::class, 'sale_contract_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
