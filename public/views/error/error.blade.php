@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover" class="Page_showPage">
<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
<tr>
<td>	
<table class="Page-query-noresualt">
<tr>
<td>
<span class="not-found">
{{ preg_replace('#"([^"]+)"#', sprintf('<u><font color="#000000">"%s"</font></u>', '$1'), $message) }}
</span>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop