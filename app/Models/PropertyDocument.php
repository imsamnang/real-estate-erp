<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyDocument extends Model
{
    use SoftDeletes;

    protected $table = 'property_documents';

    protected $fillable = ['property_id', 'document_type', 'document_no', 'file_path', 'issue_date', 'expiry_date', 'note', 'uploaded_by'];

    protected function casts(): array
    {
        return ['property_id' => 'integer', 'issue_date' => 'date', 'expiry_date' => 'date', 'uploaded_by' => 'integer'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
