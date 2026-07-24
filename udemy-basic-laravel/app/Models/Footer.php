<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Footer extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'short_description',
        'address',
        'email',
        'facebook',
        'twitter',
        'linkedin',
        'behance',
        'instagram',
        'copyright',
    ];
}
