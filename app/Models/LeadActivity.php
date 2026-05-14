<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    protected $table = 'lead_activities';

    protected $fillable = ['lead_id', 'activity_type', 'description', 'activity_at', 'next_follow_up_at', 'created_by'];

    protected function casts(): array
    {
        return ['lead_id' => 'integer', 'activity_at' => 'datetime', 'next_follow_up_at' => 'datetime', 'created_by' => 'integer'];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
