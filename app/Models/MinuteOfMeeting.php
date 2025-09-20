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
        'content',
        'created_by',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function actionItems()
    {
        return $this->hasMany(ActionItem::class, 'minutes_id');
    }
}
