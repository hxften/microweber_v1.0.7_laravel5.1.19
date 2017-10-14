<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersSms extends Model
{
    public $table = 'users_sms';

	protected $fillable = array(
        'username',
        'email',
        'areacode',
        'phone',
        'taskid',
        'lang',
        'message',
        'content',
        'verification_code',
	);


    public static function boot()
    {
        parent::boot();
    }
}
