<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $publish_users = [
        'mike.minckler@brentwood.ca',
        'ian.mcpherson@brentwood.ca',
        'cheryl.murtland@brentwood.bc.ca',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function groups()
    {
        return session()->get('groups');
    }

    public function hasGroup($group)
    {
        return session()->get('user_groups')->contains($group);
    }

    public function canPublish()
    {
        return collect($this->publish_users)->contains(auth()->user()->email);
    }
}
