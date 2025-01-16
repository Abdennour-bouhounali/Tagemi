<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'date', 'description'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function specialties()
    {
        return $this->hasMany(Specialty::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
