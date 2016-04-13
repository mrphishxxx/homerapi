<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Login extends Eloquent{
	
	use SoftDeletes;
	
	protected $hidden = ['id', 'user_id'];

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}

}

?>