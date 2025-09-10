<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location','capacity'];
 
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
    public function minutesOfMeeting()
    {
        return $this->hasMany(MinuteOfMeeting::class);
    }
}
