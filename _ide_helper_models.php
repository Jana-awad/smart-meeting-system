<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property string $file_name
 * @property string $file_path
 * @property string $file_type
 * @property int $uploaded_by
 * @property int|null $minute_of_meeting_id
 * @property string|null $uploaded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MinuteOfMeeting|null $minuteOfMeeting
 * @property-read \App\Models\User $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereMinuteOfMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUploadedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUploadedBy($value)
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Attendee
 *
 * @property int $id
 * @property int $meeting_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Meeting $meeting
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendee whereUserId($value)
 */
	class Attendee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Feature
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feature whereUpdatedAt($value)
 */
	class Feature extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Meeting
 *
 * @property int $id
 * @property int $room_id
 * @property int $organized_by
 * @property string $booking_start
 * @property string $booking_end
 * @property string|null $title
 * @property string $status
 * @property string|null $agenda
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $attendees
 * @property-read int|null $attendees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MinuteOfMeeting> $minutesOfMeeting
 * @property-read int|null $minutes_of_meeting_count
 * @property-read \App\Models\User $organizer
 * @property-read \App\Models\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereAgenda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereBookingEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereBookingStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereOrganizedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meeting whereUpdatedAt($value)
 */
	class Meeting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MinuteOfMeeting
 *
 * @property int $id
 * @property int $meeting_id
 * @property int $room_id
 * @property int $created_by
 * @property int $assigned_to
 * @property string|null $content
 * @property string|null $description
 * @property string $status
 * @property string|null $issues
 * @property string|null $deadline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $assignee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Meeting $meeting
 * @property-read \App\Models\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereIssues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MinuteOfMeeting whereUpdatedAt($value)
 */
	class MinuteOfMeeting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $content
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Room
 *
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Feature> $features
 * @property-read int|null $features_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meeting> $meetings
 * @property-read int|null $meetings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MinuteOfMeeting> $minutesOfMeeting
 * @property-read int|null $minutes_of_meeting_count
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUpdatedAt($value)
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MinuteOfMeeting> $assignedMinutes
 * @property-read int|null $assigned_minutes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meeting> $attendees
 * @property-read int|null $attendees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MinuteOfMeeting> $createdMinutes
 * @property-read int|null $created_minutes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $createdRooms
 * @property-read int|null $created_rooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meeting> $organizedMeetings
 * @property-read int|null $organized_meetings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject {}
}

