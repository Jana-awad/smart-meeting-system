<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'organized_by',
        'booking_start',
        'booking_end',
        'title',
        'status',
        'agenda',
    ];
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organized_by');
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function minutesOfMeeting()
    {
        return $this->hasMany(MinuteOfMeeting::class);
    }

    // public function attendees()
    // {
    //     return $this->belongsToMany(User::class);
    // }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'attendees', 'meeting_id', 'user_id')
            ->withTimestamps();
    }
}
