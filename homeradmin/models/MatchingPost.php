<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class MatchingPost extends Eloquent{
	
	protected $primaryKey = 'mid';

	protected $table = 'matchingposts';
    
    public function postFrom(){
        return $this->belongsTo('Post', 'post_from');
    }
    
    public function postTo(){
        return $this->belongsTo('Post', 'post_to');
    }

}

?>