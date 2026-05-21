<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = ['company_id', 'branch_id', 'customer_id', 'lead_no', 'name', 'phone', 'email', 'source', 'interested_property_type', 'budget_min', 'budget_max', 'note', 'assigned_to', 'status', 'next_follow_up_at', 'converted_at'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'customer_id' => 'integer', 'budget_min' => 'decimal:2', 'budget_max' => 'decimal:2', 'assigned_to' => 'integer', 'next_follow_up_at' => 'datetime', 'converted_at' => 'datetime'];
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

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
