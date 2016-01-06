<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Verification extends Eloquent{
		
	protected $hidden = ['id', 'user_id'];

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}

}

?>