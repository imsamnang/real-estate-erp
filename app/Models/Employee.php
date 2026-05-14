<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = ['user_id', 'company_id', 'branch_id', 'department_id', 'employee_code', 'name', 'gender', 'dob', 'phone', 'email', 'address', 'hire_date', 'salary', 'status'];

    protected function casts(): array
    {
        return ['user_id' => 'integer', 'company_id' => 'integer', 'branch_id' => 'integer', 'department_id' => 'integer', 'dob' => 'date', 'hire_date' => 'date', 'salary' => 'decimal:2'];
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
