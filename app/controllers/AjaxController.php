<?php

class AjaxController extends BaseController {
	
	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	public function showAjax()
	{
		$query = Input::get('query', '');
		
		if ($query)
		{
			$rows = Helpers::getSuggestions($query, Config::get('app_settings.results_per_page', 10));
			
			$count = 1;
			$output = '';
			
			foreach($rows as $row)
			{
				$output .= sprintf('<li class="ui-menu-item" role="menuitem" id="s%s" onmouseover="srch_itm=%s;mark_s(\'s%s\')" onmouseout="unmark_s(\'s%s\')" onclick="select_s(\'s%s\',1)" title="%s"><span class="ui-corner-all" tabindex="-1">%s</span></li>', $count, $count, $count, $count, $count, $row['value'], $row['label']);
				
				++$count;
			}
			
			return $output;
		}
	}
}