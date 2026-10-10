<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SobiDigiposData extends Model
{
    use HasFactory;

    protected $fillable = ['row_index', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
