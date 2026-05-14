<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTeam extends Model
{
    use SoftDeletes;

    protected $table = 'sales_teams';

    protected $fillable = ['branch_id', 'name', 'leader_id', 'status'];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'leader_id' => 'integer'];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
}
