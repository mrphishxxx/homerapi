<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class MatchingPost extends Eloquent{
	
	public $timestamps = false;

	protected $primaryKey = 'mid';

	protected $table = 'matchingposts';

}

?>