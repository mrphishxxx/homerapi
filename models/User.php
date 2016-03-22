<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Eloquent{

	use SoftDeletes;

	protected $visible = ['id', 'full_name', 'avatar', 'quickblox_id'];

	public function posts(){
		return $this->hasMany('Post', 'user_id');
	}

	public function logins(){
		return $this->hasMany('Login', 'user_id');
	}

	public function viewedMatches(){
		return $this->belongsToMany('MatchingPost', 'postviews', 'user_id', 'post_id');
	}

	public function ratings(){
		return $this->hasMany('UserRating', 'user_to');
	}

}

?>