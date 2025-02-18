<?php


namespace App\Models; // Ensure correct namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Client extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'name', 'email', 'phone'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

