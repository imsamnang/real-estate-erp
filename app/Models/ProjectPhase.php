<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPhase extends Model
{
    use SoftDeletes;

    protected $table = 'project_phases';

    protected $fillable = ['project_id', 'phase_code', 'name', 'description', 'start_date', 'end_date', 'status'];

    protected function casts(): array
    {
        return ['project_id' => 'integer', 'start_date' => 'date', 'end_date' => 'date'];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
