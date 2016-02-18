<?php
//Copyright (c) 2016 "Werner Maisl"
//
//This file is part of the Aurora Webinterface
//
//The Aurora Webinterface is free software: you can redistribute it and/or modify
//it under the terms of the GNU Affero General Public License as
//published by the Free Software Foundation, either version 3 of the
//License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU Affero General Public License for more details.
//
//You should have received a copy of the GNU Affero General Public License
//along with this program. If not, see <http://www.gnu.org/licenses/>.


namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateableContract;
use Illuminate\Database\Eloquent\Model;

class ForumUserModel extends Model implements AuthenticateableContract
{
    use Authenticatable;
    protected $connection = 'forum';
    protected $table = 'phpbb_users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $enable_remember_me = false; //If remember me should be enabled
    protected $remember_token_name = "remember_me";

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return "user_id";
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        if($this->enable_remember_me)
        {
            return $this->remember_token;
        }
        else
        {
            return null;
        }
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if($this->enable_remember_me)
        {
            $this->remember_token = $value;
            $this->save();
        }
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->remember_token_name;
    }

}