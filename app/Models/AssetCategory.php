<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCategory extends Model
{
    use SoftDeletes;

    protected $table = 'asset_categories';

    protected $fillable = ['name', 'code', 'status'];

    protected function casts(): array
    {
        return [];
    }
}
