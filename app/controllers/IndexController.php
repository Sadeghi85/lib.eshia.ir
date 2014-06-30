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

	// Catch all
	public function showPage()
	{
		end($this->_navigationTabs);
		$lastKey = key($this->_navigationTabs);
		end($this->_navigationTabs[$lastKey]);
		$lastKey = key($this->_navigationTabs[$lastKey]);
		
		// ----query_not_found
		if ($this->_navigationSegments[0] != '' and $this->_navigationTabs[count($this->_navigationTabs)][$lastKey - 1]['selected'] == true and $this->_navigationSegments[count($this->_navigationSegments) - 1] != 'all')
		{
			$resultCount = 0;
			$query = $this->_navigationSegments[count($this->_navigationSegments) - 1];
			
			return View::make('query')->with(compact('resultCount', 'query'));
		}
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		// ----index
		if ($this->_navigationSegments[0] == '')
		{
			$xpathQuery = sprintf('//%s', BOOK_NODE);
			$books = $xpath->query($xpathQuery, $this->_xmlObject);
			$xpathQuery = sprintf('//%s', VOL_NODE);
			$vols = $xpath->query($xpathQuery, $this->_xmlObject);
			
			$booksCount = $books->length;
			$volsCount = $vols->length;

			return View::make('index')->with(compact('booksCount', 'volsCount'));
		}
		
		// ----author_booklist
		if ($this->_navigationTabs[count($this->_navigationTabs)][$lastKey]['selected'] == true and $this->_navigationSegments[count($this->_navigationSegments) - 1] != 'authors')
		{
			$author = $this->_navigationSegments[count($this->_navigationSegments) - 1];
			
			$xpathQuery = sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_AUTHOR, $author);
			
			$books = $xpath->query($xpathQuery, $this->_xmlObject);
			
			$tempBookArray = array('name' => array(), 'displayname' => array(), 'vols' => array());
			
			foreach ($books as $bookNode)
			{
				$bookNode = simplexml_import_dom($bookNode);
				$volCount = 0;
				$name = (string) $bookNode[BOOK_ATTR_NAME];
				$displayName = (string) $bookNode[BOOK_ATTR_DISPLAYNAME];
				
				foreach ($bookNode->volumes[0]->vol as $vol)
				{
					++$volCount;
				}
	
				$tempBookArray['name'][$name] = $name;
				$tempBookArray['displayname'][$name] = $displayName;
				$tempBookArray['vols'][$name] = $volCount;
			}
			
			$tempSortedArray = array_map('Helpers::persianizeString', $tempBookArray['displayname']);
			$tempSortedArray = Helpers::psort($tempSortedArray);
			$tempSynchedArray = array();
			foreach ($tempSortedArray as $key => $value)
			{
				$tempSynchedArray[$key] = $tempBookArray['displayname'][$key];
			}
			$tempBookArray['displayname'] = $tempSynchedArray;
			
			$books = array();
			
			foreach (array_keys($tempBookArray['displayname']) as $id)
			{
				$books[] = array(
					'id'     => $tempBookArray['name'][$id],
					'name'   => $tempBookArray['displayname'][$id],
					'vols'   => $tempBookArray['vols'][$id]
				);
			}
			
			return View::make('author_booklist')->with(compact('books', 'author'));
		}
	
	
	
	
		return View::make('hello');
	
	
	}
}