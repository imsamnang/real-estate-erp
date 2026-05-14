<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTeamMember extends Model
{
    protected $table = 'sales_team_members';

    protected $fillable = ['sales_team_id', 'user_id', 'role', 'joined_at', 'status'];

    protected function casts(): array
    {
        return ['sales_team_id' => 'integer', 'user_id' => 'integer', 'joined_at' => 'date'];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(SalesTeam::class, 'sales_team_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
