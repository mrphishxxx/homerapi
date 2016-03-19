<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SimilarPost extends Eloquent{

	protected $table = 'similarposts';
    
    protected $primaryKey = 'sid';
    
    public function postFrom(){
        return $this->belongsTo('Post', 'post_from');
    }
    
    public function postTo(){
        return $this->belongsTo('Post', 'post_to');
    }

}

?>