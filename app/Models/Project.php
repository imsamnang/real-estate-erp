<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = ['company_id', 'branch_id', 'project_code', 'name', 'project_type', 'description', 'location', 'province', 'district', 'commune', 'village', 'latitude', 'longitude', 'start_date', 'expected_finish_date', 'actual_finish_date', 'total_units', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['company_id' => 'integer', 'branch_id' => 'integer', 'latitude' => 'decimal:2', 'longitude' => 'decimal:2', 'start_date' => 'date', 'expected_finish_date' => 'date', 'actual_finish_date' => 'date', 'total_units' => 'integer', 'created_by' => 'integer'];
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
}
