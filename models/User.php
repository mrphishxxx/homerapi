<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Eloquent{

	use SoftDeletes;

	public function records(){
		return $this->hasMany('Record', 'user_id');
	}

	public function logins(){
		return $this->hasMany('Login', 'user_id');
	}

}

?>