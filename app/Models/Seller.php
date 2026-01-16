<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class Seller extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'country',
        'state',
        'skills',
        'password'
    ];

    protected $casts = [
        'skills' => 'array'
    ];

    protected $hidden = [
        'password'
    ];
}