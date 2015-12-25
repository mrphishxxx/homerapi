<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserRating extends Eloquent{
	
	protected $table = 'userratings';

	public function userFrom(){
		return $this->belongsTo('User', 'user_from');
	}

	public function userTo(){
		return $this->belongsTo('User', 'user_to');
	}

}

?>