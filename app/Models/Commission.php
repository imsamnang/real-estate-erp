<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;

    protected $table = 'commissions';

    protected $fillable = ['company_id', 'branch_id', 'sale_contract_id', 'user_id', 'commission_no', 'commission_type', 'commission_rate', 'sale_amount', 'commission_amount', 'status'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'sale_contract_id' => 'integer', 'user_id' => 'integer', 'commission_rate' => 'decimal:2', 'sale_amount' => 'decimal:2', 'commission_amount' => 'decimal:2'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(SaleContract::class, 'sale_contract_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
