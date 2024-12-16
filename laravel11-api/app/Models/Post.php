<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'title',      // Memperbaiki "tittle" menjadi "title"
        'content',
        'image',      // Menambahkan atribut image
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', // Hide the password attribute when converting to arrays or JSON
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // You may add this field if you plan to use email verification
    ];
}
