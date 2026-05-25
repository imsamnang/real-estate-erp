<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $table = 'journal_entries';

    protected $fillable = ['company_id', 'branch_id', 'entry_no', 'entry_date', 'reference_type', 'reference_id', 'description', 'status', 'created_by', 'posted_by', 'posted_at'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'entry_date' => 'date', 'reference_id' => 'integer', 'created_by' => 'integer', 'posted_by' => 'integer', 'posted_at' => 'datetime'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
