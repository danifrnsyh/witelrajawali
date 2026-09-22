<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardData extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
