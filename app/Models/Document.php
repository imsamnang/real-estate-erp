<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $table = 'documents';

    protected $fillable = ['company_id', 'branch_id', 'documentable_type', 'documentable_id', 'document_no', 'document_type', 'title', 'file_path', 'version', 'issue_date', 'expiry_date', 'status', 'uploaded_by', 'approved_by', 'approved_at'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'documentable_id' => 'integer', 'issue_date' => 'date', 'expiry_date' => 'date', 'uploaded_by' => 'integer', 'approved_by' => 'integer', 'approved_at' => 'datetime'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
