<?php namespace Sadeghi85\Extensions;

class Request extends \Illuminate\Http\Request {

	/**
	 * Get the current path info for the request.
	 *
	 * @return string
	 */
    public function path()
	{
		$pattern = urldecode(str_replace('_', '%20', trim($this->getPathInfo(), '/')));
		
		return $pattern == '' ? '/' : $pattern;
	}
	
	/**
	 * Get a segment from the URI (1 based index).
	 *
	 * @param  string  $index
	 * @param  mixed   $default
	 * @return string
	 */
	public function segment($index, $default = null)
	{
		return array_get($this->segments(), $index, $default);
	}

	/**
	 * Get all of the segments for the request path.
	 *
	 * @return array
	 */
	public function segments()
	{
		$segments = explode('/', $this->path());
		
		$segments = array_values(array_filter($segments, function($v) { return $v != ''; }));
		
		array_unshift($segments, '');
		unset($segments[0]);
		
		return $segments;
	}

}