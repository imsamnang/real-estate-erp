<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = ['user_id', 'company_id', 'branch_id', 'action', 'module', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'ip_address', 'user_agent'];

    protected function casts(): array
    {
        return ['user_id' => 'integer', 'company_id' => 'integer', 'branch_id' => 'integer', 'auditable_id' => 'integer', 'old_values' => 'array', 'new_values' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
