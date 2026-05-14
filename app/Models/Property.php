<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'properties';

    protected $fillable = ['company_id', 'branch_id', 'project_id', 'project_phase_id', 'property_type_id', 'owner_id', 'property_code', 'title', 'description', 'unit_no', 'floor_no', 'block_no', 'street_no', 'size_width', 'size_length', 'land_area', 'building_area', 'bedrooms', 'bathrooms', 'direction', 'hard_title_no', 'soft_title_no', 'base_price', 'sale_price', 'rent_price', 'currency', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'project_id' => 'integer', 'project_phase_id' => 'integer', 'property_type_id' => 'integer', 'owner_id' => 'integer', 'size_width' => 'decimal:2', 'size_length' => 'decimal:2', 'land_area' => 'decimal:2', 'building_area' => 'decimal:2', 'bedrooms' => 'integer', 'bathrooms' => 'integer', 'base_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'rent_price' => 'decimal:2', 'created_by' => 'integer'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class, 'project_phase_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
