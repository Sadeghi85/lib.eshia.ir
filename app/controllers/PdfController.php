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
		
		foreach ($books as $bookNode)
		{
			$bookName = $bookNode->getAttribute(BOOK_ATTR_DISPLAYNAME);
		}
		
		return View::make('pdf', compact('id', 'volume', 'bookName'));
	}
}