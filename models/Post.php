<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Eloquent{
	
	use SoftDeletes;
	
	protected $hidden = ['id', 'user_id'];

	protected $appends = ['post_time'];

	public function user(){
		return $this->belongsTo('User', 'user_id');
	}

	public function getPostTimeAttribute(){
		if ($this->created_at != NULL){
			return time_elapsed_string($this->created_at);
		}
		return '';
	}

}

?>