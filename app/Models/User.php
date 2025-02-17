<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Users can have many products (if needed, depending on the use case)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',     // Allow mass-assignment for the name field
        'email',    // Allow mass-assignment for the email field
        'password', // Allow mass-assignment for the password field
    ];
}
