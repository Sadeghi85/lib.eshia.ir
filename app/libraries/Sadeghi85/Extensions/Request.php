<?php namespace Sadeghi85\Extensions;

class Request extends \Illuminate\Http\Request {

    public function path()
	{
		$pattern = urldecode(str_replace('_', '%20', trim($this->getPathInfo(), '/')));
		
		return $pattern == '' ? '/' : $pattern;
	}

}