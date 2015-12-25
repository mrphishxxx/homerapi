<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Eloquent{
	
	use SoftDeletes;
	
	protected $hidden = ['matchedPosts', 'matchingPosts', 'similarTo', 'similarFrom'];

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

	public function matchedPosts(){
		return $this->belongsToMany('Post', 'matchingposts', 'post_from', 'post_to');
	}

	public function matchingPosts(){
		return $this->belongsToMany('Post', 'matchingposts', 'post_to', 'post_from');
	}

	public function similarTo(){
		return $this->belongsToMany('Post', 'similarposts', 'post_from', 'post_to');
	}

	public function similarFrom(){
		return $this->belongsToMany('Post', 'similarposts', 'post_to', 'post_from');
	}

}

?>