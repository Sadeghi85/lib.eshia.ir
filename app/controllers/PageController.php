<?php

class PageController extends BaseController {

	protected $_persianizedXMLObject = null;
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
	public function showPage($num = 0)
	{

		//return View::make('help/help-'.$num);
	}
}