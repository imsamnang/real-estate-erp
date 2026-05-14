<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    protected $table = 'approval_steps';

    protected $fillable = ['approval_request_id', 'step_no', 'approver_id', 'status', 'comment', 'acted_at'];

    protected function casts(): array
    {
        return ['approval_request_id' => 'integer', 'step_no' => 'integer', 'approver_id' => 'integer', 'acted_at' => 'datetime'];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
