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
        'cheryl.murtland@brentwood.ca',
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
        if (session()->has('user_groups')) {
            return session()->get('user_groups')->contains($group);
        } else {
            return false;
        }
    }

    public function canPublish()
    {
        return collect($this->publish_users)->contains(auth()->user()->email);
    }

    public static function OAuthCheck()
    {

        if (!session()->has('user_groups')) {
            auth()->logout();
            session()->invalidate();
            return redirect()->route('home');
        }
        
        return false;
    }
}
