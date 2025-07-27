<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteOfMeeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'meeting_id',
        'room_id',
        'created_by',
        'assigned_to',
        'content',
        'description',
        'status',
        'issues',
        'deadline',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');            
}
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
