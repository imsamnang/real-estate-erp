<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    protected $table = 'property_images';

    protected $fillable = ['property_id', 'image_path', 'caption', 'is_primary', 'sort_order'];

    protected function casts(): array
    {
        return ['property_id' => 'integer', 'is_primary' => 'boolean', 'sort_order' => 'integer'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
