<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalContract extends Model
{
    use SoftDeletes;

    protected $table = 'rental_contracts';

    protected $fillable = ['company_id', 'branch_id', 'customer_id', 'property_id', 'contract_no', 'start_date', 'end_date', 'monthly_rent', 'deposit_amount', 'payment_cycle', 'status', 'created_by', 'approved_by'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'customer_id' => 'integer', 'property_id' => 'integer', 'start_date' => 'date', 'end_date' => 'date', 'monthly_rent' => 'decimal:2', 'deposit_amount' => 'decimal:2', 'created_by' => 'integer', 'approved_by' => 'integer'];
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
