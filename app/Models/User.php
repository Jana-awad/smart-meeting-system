<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //public function roles()
    //{
   //     return $this->belongsToMany(Role::class, 'role_user')->withTimestamps()->withPivot('assigned_at');
   // }
//
    public function createdRooms()
    {
        return $this->hasMany(Room::class, 'created_by');
    }

    public function organizedMeetings()
    {
        return $this->hasMany(Meeting::class, 'organized_by');
    }

    public function createdMinutes()
    {
        return $this->hasMany(MinuteOfMeeting::class, 'created_by');
    }

    public function assignedMinutes()
    {
        return $this->hasMany(MinuteOfMeeting::class, 'assigned_to');
    }

    public function attendees()
    {
        return $this->belongsToMany(Meeting::class, 'attendees');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
     public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employee';
    }
    /**
 * Override default notification to send a frontend reset link.
 *
 * This builds the URL that the frontend will open (reset page).
 */
public function sendPasswordResetNotification($token)
{
    // FRONTEND_URL is a new env var (see .env instructions below)
    $frontend = config('app.frontend_url', env('FRONTEND_URL', 'http://127.0.0.1:5500'));
    $url = rtrim($frontend, '/') . '/reset-password.html?token=' . $token . '&email=' . urlencode($this->email);

    // Use a custom notification class (see next file)
    $this->notify(new \App\Notifications\ResetPasswordNotification($url));
}

}
