<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    protected $table = 'action_items';

    protected $fillable = [
        'minutes_id',
        'description',
        'assigned_to',
        'status',
        'deadline',
    ];

    // Relationships

    public function minutes()
    {
        return $this->belongsTo(MinuteOfMeeting::class, 'minutes_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
