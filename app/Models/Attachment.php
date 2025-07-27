<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['file_name', 'file_path', 'file_type', 'uploaded_by', 'minute_of_meeting_id', 'uploaded_at'];

    public function minuteOfMeeting()
    {
        return $this->belongsTo(MinuteOfMeeting::class);
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
