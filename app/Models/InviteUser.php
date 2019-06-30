<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 被邀请人记录model
 * @author admin
 *
 */
class InviteUser extends Model
{
    protected $table = 't_invite_user';

  	protected $guarded = ['id'];


}
