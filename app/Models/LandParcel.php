<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandParcel extends Model
{
    use SoftDeletes;

    protected $table = 'land_parcels';

    protected $fillable = ['company_id', 'branch_id', 'project_id', 'parcel_code', 'title_no', 'location', 'province', 'district', 'commune', 'village', 'total_area', 'purchase_price', 'purchase_date', 'owner_name', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'project_id' => 'integer', 'total_area' => 'decimal:2', 'purchase_price' => 'decimal:2', 'purchase_date' => 'date', 'created_by' => 'integer'];
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
