<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
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
        'admin',
        'activation_digest',
        'activated',
        'activated_at',
        'remember_token',
        'reset_digest',
        'reset_sent_at',
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
        'password' => 'hashed',
        'admin' => 'boolean',
        'activated' => 'boolean',
        'activated_at' => 'datetime',
        'reset_sent_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->remember_token = Str::random(60);
        });
    }

    /**
     * Get the microposts for the user.
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the users that this user is following.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'relationships', 'follower_id', 'followed_id');
    }

    /**
     * Get the users that are following this user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'relationships', 'followed_id', 'follower_id');
    }

    /**
     * Follow a user.
     */
    public function follow($otherUser)
    {
        return $this->following()->attach($otherUser);
    }

    /**
     * Unfollow a user.
     */
    public function unfollow($otherUser)
    {
        return $this->following()->detach($otherUser);
    }

    /**
     * Check if the user is following another user.
     */
    public function isFollowing($otherUser)
    {
        return $this->following()->where('followed_id', $otherUser->id)->exists();
    }

    /**
     * Get the feed for the user.
     */
    public function feed()
    {
        $followingIds = $this->following()->pluck('users.id')->toArray();
        $followingIds[] = $this->id;

        return Micropost::whereIn('user_id', $followingIds)
                        ->orWhere('user_id', $this->id)
                        ->latest()
                        ->get();
    }
}
