@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<br /><br />
<div id="contents">
<div id="contents_cover" class="Page_showPage">
	<div class="shenasnameh">
		<span>{{ $content }}</span>
	</div>
</div>
</div>
@stop

@section('title')
	@parent
@stop