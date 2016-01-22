@extends('layouts.default')

@section('content')
<div id="contents" class="content-DefaultPage">
		<table id="content-DefaultPage-Table" cellpadding="0" cellspacing="0">
			<tr>
				<td class="DefaultPage-Board">
					<div class="welcome">
						<span>@lang('app.index_welcome')</span>
						<br />
						<span>@lang('app.books_information', array('books_count' => sprintf('<span class="book_Counter_Page1"> %s </span>', $booksCount), 'vols_count' => sprintf('<span class="book_Counter_Page1"> %s </span>', $volsCount)))</span>
					</div>
				</td>
			</tr>
		</table>    
</div>

<div class="DefaultPage-down">
	<span>	
		@lang('app.index_attention')
	<br/>
		<a href="/help">@lang('app.index_attention_lib_help', array('help' => sprintf('<font color="#FF6633">%s</font>', trans('app.lib_help'))))</a>
	</span>
</div>
@stop

@section('title')
	@parent
@stop