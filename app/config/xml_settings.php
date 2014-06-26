<?php

switch (Config::get('app.locale'))
{
	case 'fa':
		$group_attr_name = 'name';
		break;
	case 'ar':
		$group_attr_name = 'arname';
		break;
	case 'en':
		$group_attr_name = 'name';
		break;
	default:
		$group_attr_name = 'name';
		break;
}

return array(

	'main_node' => 'Books',
	'group_node' => 'group',
	'group_attr_name' => $group_attr_name,
	'group_attr_order' => 'order',
	'book_node' => 'book',
	'book_attr_name' => 'name',
	'book_attr_displayname' => 'DisplayName',
	'book_attr_author' => 'author',
	'volumes_node' => 'volumes',
	'vol_node' => 'vol',
	'vol_attr_id' => 'id',
	'vol_attr_base' => 'base',
	'vol_attr_pages' => 'pages',
	'vol_attr_index' => 'indexPage',

);