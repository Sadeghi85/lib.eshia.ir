<?php

class IndexController extends BaseController {

	protected $_navigationTabs = null;
	protected $_navigationSegments = null;
	protected $_persianizedXMLObject = null;
	protected $_xmlObject = null;

	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_navigationTabs = Session::get('navigation.tabs');
		$this->_navigationSegments = Session::get('navigation.segments');
		$this->_xmlObject = Helpers::getXMLObject();
		// $this->_persianizedXMLObject = Helpers::getPersianizedXMLObject();
		
	}
	
	public function showIndex()
	{
		$xpath = new DOMXpath($this->_xmlObject);
		
        $xpath_query = sprintf('//%s', BOOK_NODE);
		$books = $xpath->query($xpath_query, $this->_xmlObject);
		$xpath_query = sprintf('//%s', VOL_NODE);
		$vols = $xpath->query($xpath_query, $this->_xmlObject);
		
		$booksCount = $books->length;
		$volsCount = $vols->length;

		return View::make('index')->with(compact('booksCount', 'volsCount'));
	}
	
	public function showTheRest()
	{
		//dd($this->_navigationTabs[count($this->_navigationTabs)][count($this->_navigationTabs[count($this->_navigationTabs)])]);
		end($this->_navigationTabs[count($this->_navigationTabs)]);
		$last_key = key($this->_navigationTabs[count($this->_navigationTabs)]);
		
		if ($this->_navigationTabs[count($this->_navigationTabs)][$last_key - 1]['selected'] == TRUE && $this->_navigationSegments[count($this->_navigationSegments) - 1] != 'all')
		
		//if ($this->_navigationTabs[count($this->_navigationTabs)][count($this->_navigationTabs[count($this->_navigationTabs)])]['selected'] == TRUE and $this->_navigationSegments[count($this->_navigationSegments) - 1] != 'all')
		{
			$resultCount = 0;
			$query = $this->_navigationSegments[count($this->_navigationSegments) - 1];
			
			return View::make('query')->with(compact('resultCount', 'query'));
		}
	
	
		return View::make('hello');
	
	
	}
}