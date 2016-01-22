<?php

class IndexController extends BaseController {
	
	protected $_navigationTabs = null;
	protected $_navigationSegments = null;
	
	protected $_xmlObject = null;
	
	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_navigationTabs = Helpers::getNavigationTabs();
		$this->_navigationSegments = Helpers::getNavigationSegments();
		
		$this->_xmlObject = Helpers::getXMLObject();
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
			if (is_readable(sprintf('%s\\%s', Config::get('app_settings.images_path'), Request::Path())))
			{
				# Static file
				return Redirect::to(Config::get('app_settings.images_url').'/'.Request::Path(), 301, ['Cache-Control' => 'public,max-age=86400']);
				
			}
			
			Helpers::setExceptionErrorMessage(Lang::get(sprintf('%s/app.query_search_result_not_found', Config::get('app_settings.theme')), array('query' => sprintf('"%s"', $this->_navigationSegments[count($this->_navigationSegments) - 1]))));
			
			App::abort(404);
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

			//return View::make('index')->with(compact('booksCount', 'volsCount'));
			$this->layout->content = View::make(sprintf('%s/index', Config::get('app_settings.theme')), compact('booksCount', 'volsCount'));
			return;
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
					if (((int) $vol[VOL_ATTR_ID]) != 0 )
					{
						++$volCount;
					}
				}
	
				$tempBookArray['name'][$name] = $name;
				$tempBookArray['displayname'][$name] = $displayName;
				$tempBookArray['vols'][$name] = $volCount;
			}
			
			$tempBookArray['displayname'] = Helpers::psort($tempBookArray['displayname']);
			
			$books = array();
			
			foreach (array_keys($tempBookArray['displayname']) as $id)
			{
				$books[] = array(
					'id'     => $tempBookArray['name'][$id],
					'name'   => $tempBookArray['displayname'][$id],
					'vols'   => $tempBookArray['vols'][$id]
				);
			}
			
			//return View::make('author_booklist')->with(compact('books', 'author'));
			$this->layout->content = View::make(sprintf('%s/author_booklist', Config::get('app_settings.theme')), compact('books', 'author'));
			return;
		}
		
		$xpathQuery = '//';
		
		foreach($this->_navigationSegments as $groupName)
		{
			switch ($groupName)
			{
				case 'authors':
					$xpathQuery .= sprintf('/%s/', GROUP_NODE);
					break;
					
				case 'all':
					$xpathQuery .= sprintf('/%s/', GROUP_NODE);
					break;
					
				default:
					$xpathQuery .= sprintf('%s[@%s=\'%s\']/', GROUP_NODE, GROUP_ATTR_NAME, $groupName);
			}
		}
		
		$xpathQuery .= BOOK_NODE;

		// ----authorlist
		if ($this->_navigationSegments[count($this->_navigationSegments) - 1] == 'authors')
		{
			$books = $xpath->query($xpathQuery, $this->_xmlObject);
			
			$tempBookArray = array('author' => array());
			
			foreach ($books as $bookNode)
			{
				$tempBookArray['author'][$bookNode->getAttribute(BOOK_ATTR_NAME)] = $bookNode->getAttribute(BOOK_ATTR_AUTHOR);
			}
			
			$books = $xpath->query(sprintf('//%s', BOOK_NODE), $this->_xmlObject);
			
			foreach ($books as $bookNode)
			{
				$tempBookArray['all_authors'][$bookNode->getAttribute(BOOK_ATTR_NAME)] = $bookNode->getAttribute(BOOK_ATTR_AUTHOR);
			}
			
			$authorsBooksCount = array_count_values($tempBookArray['all_authors']);
			
			foreach ($authorsBooksCount as $key => $value)
			{
				if ( ! in_array($key, $tempBookArray['author'])) unset($authorsBooksCount[$key]);
			}
			
			$authorsBooksCount = Helpers::kpsort($authorsBooksCount);
			
			//return View::make('authorlist')->with(compact('authorsBooksCount'));
			$this->layout->content = View::make(sprintf('%s/authorlist', Config::get('app_settings.theme')), compact('authorsBooksCount'));
			return;
		}
		// ----booklist
		else
		{
			$books = $xpath->query($xpathQuery, $this->_xmlObject);
			
			$tempBookArray = array(
				'name'        => array(),
				'displayname' => array(),
				'author'      => array(),
				'vols'        => array()
			);
			
			foreach ($books as $bookNode)
			{
				$bookNode = simplexml_import_dom($bookNode);
				$volCount = 0;
				$name = (string) $bookNode[BOOK_ATTR_NAME];
				$displayName = (string) $bookNode[BOOK_ATTR_DISPLAYNAME];
				$author = (string) $bookNode[BOOK_ATTR_AUTHOR];
				
				foreach ($bookNode->volumes[0]->vol as $vol)
				{
					if (((int) $vol[VOL_ATTR_ID]) != 0 )
					{
						++$volCount;
					}
				}
	
				$tempBookArray['name'][$name] = $name;
				$tempBookArray['displayname'][$name] = $displayName;
				$tempBookArray['author'][$name] = $author;
				$tempBookArray['vols'][$name] = $volCount;
			}
			
			$tempBookArray['displayname'] = Helpers::psort($tempBookArray['displayname']);
			
			$books = array();
			
			foreach (array_keys($tempBookArray['displayname']) as $id)
			{
				$books[] = array(
					'id'     => $tempBookArray['name'][$id],
					'name'   => $tempBookArray['displayname'][$id],
					'author' => $tempBookArray['author'][$id],
					'vols'   => $tempBookArray['vols'][$id]
				);
			}
			
			//return View::make('lib/booklist')->with(compact('books'));
			$this->layout->content = View::make(sprintf('%s/booklist', Config::get('app_settings.theme')), compact('books'));
			return;
		}
	}
}