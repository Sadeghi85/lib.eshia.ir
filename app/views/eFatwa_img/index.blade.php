@section('content')
<div id="contents" class="content-DefaultPage">
		<table id="content-DefaultPage-Table" cellpadding="0" cellspacing="0">
			<tr>
				<td class="DefaultPage-Board">
					<div class="welcome">
						<span>@lang(sprintf('%s/app.index_welcome', Config::get('app_settings.theme')))</span>
						<br />
						<span>@lang(sprintf('%s/app.books_information', Config::get('app_settings.theme')), array('books_count' => sprintf('<span class="book_Counter_Page1"> %s </span>', $booksCount), 'vols_count' => sprintf('<span class="book_Counter_Page1"> %s </span>', $volsCount)))</span>
					</div>
				</td>
			</tr>
		</table>    
</div>

<div class="DefaultPage-down">
	<span>	
		@lang(sprintf('%s/app.index_attention', Config::get('app_settings.theme')))
	<br/>
		<a href="/help">@lang(sprintf('%s/app.index_attention_lib_help', Config::get('app_settings.theme')), array('help' => sprintf('<font color="#FF6633">%s</font>', trans(sprintf('%s/app.lib_help', Config::get('app_settings.theme'))))))</a>
	</span>
</div>
@stop

@section('title')
	@parent
@stop