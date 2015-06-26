<?php

class PdfController extends BaseController {
	
	protected $_xmlObject = null;
	
	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_xmlObject = Helpers::getXMLObject();
		// $this->_persianizedXMLObject = Helpers::getPersianizedXMLObject();
		
	}

	// Catch all
	public function showPage($id, $volume)
	{
		$xpath = new DOMXpath($this->_xmlObject);
		
		$xpathQuery = sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_NAME, $id);
		
		$books = $xpath->query($xpathQuery, $this->_xmlObject);
		
		if ($books->length == 0)
		{
			Helpers::setExceptionErrorMessage(Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', Request::segment(2)))));
			
			App::abort(404);
		}
		
		$book  = $books->item(0);
		$bookName = $book->getAttribute(BOOK_ATTR_DISPLAYNAME);
		
		return View::make('pdf', compact('id', 'volume', 'bookName'));
	}
}