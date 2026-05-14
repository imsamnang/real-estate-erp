<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleContract extends Model
{
    use SoftDeletes;

    protected $table = 'sale_contracts';

    protected $fillable = ['company_id', 'branch_id', 'customer_id', 'property_id', 'booking_id', 'contract_no', 'contract_date', 'sale_price', 'discount_amount', 'tax_amount', 'total_amount', 'deposit_amount', 'paid_amount', 'balance_amount', 'payment_type', 'handover_date', 'title_transfer_date', 'note', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'customer_id' => 'integer', 'property_id' => 'integer', 'booking_id' => 'integer', 'contract_date' => 'date', 'sale_price' => 'decimal:2', 'discount_amount' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total_amount' => 'decimal:2', 'deposit_amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'balance_amount' => 'decimal:2', 'handover_date' => 'date', 'title_transfer_date' => 'date', 'created_by' => 'integer'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
