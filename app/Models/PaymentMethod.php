<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = ['name', 'code', 'account_name', 'account_no', 'status'];

    protected function casts(): array
    {
        return [];
    }
}
