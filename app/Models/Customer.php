<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'job_title',
        'email_address',
        'first_name',
        'last_name',
        'registered_since',
        'phone'
    ];

}
