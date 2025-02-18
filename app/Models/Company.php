<?php


namespace App\Models; // Ensure correct namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'address'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
