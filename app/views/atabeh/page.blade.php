
@section('content')
<div id="contents">
	<div id="contents_cover" class="Page_showPage">
		<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
		<tr>	
            <td>
            <table class="Tools-Table" align="right">
				<tr>
					<td class="Tools-pdf">
						<a href="{{ sprintf('/pdf/%s/%s', $id, $volume) }}" title="@lang(sprintf('%s/app.save_this_book_to_pdf_format', Config::get('app_settings.theme')))" target="_blank">@lang(sprintf('%s/app.pdf_format', Config::get('app_settings.theme')))</a>
					</td>
					<td class="Tools-certificate">
						<!--ارجاع شناسنامه به صفحه اول کتاب-->
						<!--<a href="{{ sprintf('/shenasnameh/%s', $id) }}" title="@lang(sprintf('%s/app.book_certificate', Config::get('app_settings.theme')))" target="_blank">@lang(sprintf('%s/app.certificate', Config::get('app_settings.theme')))</a>-->
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="@lang(sprintf('%s/app.book_certificate', Config::get('app_settings.theme')))">@lang(sprintf('%s/app.certificate', Config::get('app_settings.theme')))</a>
					</td>
                    <td class="Tools-index">
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $indexPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.index_page', Config::get('app_settings.theme')))) }}">@lang(sprintf('%s/app.index_page', Config::get('app_settings.theme')))</a>
					</td>
					<td class="Tools-serach">
						<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput1').value, {{ $id }});return false;">
						<table class="Tools-serach-table"><tr><td class="Tools-serach-input-td">	
							<input id="searchContentInput1" value="@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))'; this.className='empty-search-item'}" />
						</td><td class="Tools-serach-submit-td">
							<input type="submit" value="" class="dofindButton">
						</td></tr></table>
						</form>
					</td>
				</tr>
			</table>
            </td>
			</tr>

            <tr>
            <td>
				<form method="post" action="{{ Request::url() }}" class="frmQuickAccess">
            	<!--<input type="hidden" name="id" value="{{ $id }}" />-->
				
				<table align="center" class="BookAccessPanel">
					<tr>
						<td align="center">
						@if ( ! is_null($firstPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.first_page', Config::get('app_settings.theme')))) }}">&nbsp;&nbsp;&nbsp;««{{ trans(sprintf('%s/app.first_page', Config::get('app_settings.theme'))) }}&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($prevPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $prevPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.prev_page', Config::get('app_settings.theme')))) }}">«{{ trans(sprintf('%s/app.prev_page', Config::get('app_settings.theme'))) }}</a>
						@endif
						</td>
						<td align="center">
						<table align="center" class="BookAccessPanel2">
							<tr>
								<td><span class="page-volumeIndex">&nbsp;&nbsp;&nbsp;{{ trans(sprintf('%s/app.volume', Config::get('app_settings.theme'))) }}</span>
								{{ Form::select('volume', $volumeOptions, $volume, array('class' => 'VolumeSelector')) }}
								</td>
								<td><input class="book-search-submit" type="submit" value="{{ trans(sprintf('%s/app.goto_page', Config::get('app_settings.theme'))) }}" />
								<input name="page" class="PageSelector" value="{{ $page }}" />
								</td>
							</tr>
						</table></td>
						<td align="center">
						@if ( ! is_null($nextPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $nextPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.next_page', Config::get('app_settings.theme')))) }}">{{ trans(sprintf('%s/app.next_page', Config::get('app_settings.theme'))) }}»</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($lastPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $lastPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.last_page', Config::get('app_settings.theme')))) }}">&nbsp;&nbsp;&nbsp;{{ trans(sprintf('%s/app.last_page', Config::get('app_settings.theme'))) }}»»&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
                        <td align="center"> </td>
					</tr>
				</table>
				</form>
		</td>
        </tr>

        <tr>
        <td class="Book_Title">
        	<span class="current_book_name"><font color="#eebb41">@lang(sprintf('%s/app.page_book_name', Config::get('app_settings.theme')))</font>&nbsp;{{ $bookName }}<font color="#eebb41">&nbsp;@lang(sprintf('%s/app.page_author', Config::get('app_settings.theme')))</font>&nbsp;{{ Helpers::link_to("/$authorName", $authorName, array('title' => '')) }}</span>
            <span class="current_book_Page"><font color="#eebb41">&nbsp;&nbsp;&nbsp;@lang(sprintf('%s/app.page_book_volume', Config::get('app_settings.theme')))</font>&nbsp;{{ $volume }}&nbsp;</span>
            <span class="current_book_Page"><font color="#eebb41">@lang(sprintf('%s/app.page_book_page', Config::get('app_settings.theme')))</font>&nbsp;{{ $page }}</span>
        </td>
        </tr>
    
        <tr>
        <td class="book-page-show">
		{{ $content }}
        </td>
        </tr>

        <tr>
        <td class="Book_Title">
        	<span class="current_book_name"><font color="#eebb41">@lang(sprintf('%s/app.page_book_name', Config::get('app_settings.theme')))</font>&nbsp;{{ $bookName }}<font color="#eebb41">&nbsp;@lang(sprintf('%s/app.page_author', Config::get('app_settings.theme')))</font>&nbsp;{{ Helpers::link_to("/$authorName", $authorName, array('title' => '')) }}</span>
            <span class="current_book_Page"><font color="#eebb41">&nbsp;&nbsp;&nbsp;@lang(sprintf('%s/app.page_book_volume', Config::get('app_settings.theme')))</font>&nbsp;{{ $volume }}&nbsp;</span>
            <span class="current_book_Page"><font color="#eebb41">@lang(sprintf('%s/app.page_book_page', Config::get('app_settings.theme')))</font>&nbsp;{{ $page }}</span>
        </td>
        </tr>
        
        <tr>
            <td>
            	<form method="post" action="{{ Request::url() }}" class="frmQuickAccess">
            	<!--<input type="hidden" name="id" value="{{ $id }}" />-->
				
				<table align="center" class="BookAccessPanel">
					<tr>
						<td align="center">
						@if ( ! is_null($firstPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.first_page', Config::get('app_settings.theme')))) }}">&nbsp;&nbsp;&nbsp;««{{ trans(sprintf('%s/app.first_page', Config::get('app_settings.theme'))) }}&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($prevPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $prevPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.prev_page', Config::get('app_settings.theme')))) }}">«{{ trans(sprintf('%s/app.prev_page', Config::get('app_settings.theme'))) }}</a>
						@endif
						</td>
						<td align="center">
						<table align="center" class="BookAccessPanel2">
							<tr>
								<td><span class="page-volumeIndex">&nbsp;&nbsp;&nbsp;{{ trans(sprintf('%s/app.volume', Config::get('app_settings.theme'))) }}</span>
								{{ Form::select('volume', $volumeOptions, $volume, array('class' => 'VolumeSelector')) }}
								</td>
								<td><input class="book-search-submit" type="submit" value="{{ trans(sprintf('%s/app.goto_page', Config::get('app_settings.theme'))) }}" />
								<input name="page" class="PageSelector" value="{{ $page }}" />
								</td>
							</tr>
						</table></td>
						<td align="center">
						@if ( ! is_null($nextPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $nextPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.next_page', Config::get('app_settings.theme')))) }}">{{ trans(sprintf('%s/app.next_page', Config::get('app_settings.theme'))) }}»</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($lastPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $lastPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.last_page', Config::get('app_settings.theme')))) }}">&nbsp;&nbsp;&nbsp;{{ trans(sprintf('%s/app.last_page', Config::get('app_settings.theme'))) }}»»&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
                        <td align="center"> </td>
					</tr>
				</table>
				</form>
		</td>
        </tr>
        
        <tr>	
            <td>
            <table class="Tools-Table" align="right">
				<tr>
					<td class="Tools-pdf">
						<a href="{{ sprintf('/pdf/%s/%s', $id, $volume) }}" title="@lang(sprintf('%s/app.save_this_book_to_pdf_format', Config::get('app_settings.theme')))" target="_blank">@lang(sprintf('%s/app.pdf_format', Config::get('app_settings.theme')))</a>
					</td>
					<td class="Tools-certificate">
						<!--<a href="{{ sprintf('/shenasnameh/%s', $id) }}" title="@lang(sprintf('%s/app.book_certificate', Config::get('app_settings.theme')))" target="_blank">@lang(sprintf('%s/app.certificate', Config::get('app_settings.theme')))</a>-->
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="@lang(sprintf('%s/app.book_certificate', Config::get('app_settings.theme')))">@lang(sprintf('%s/app.certificate', Config::get('app_settings.theme')))</a>
					</td>
                    <td class="Tools-index">
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $indexPage) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.index_page', Config::get('app_settings.theme')))) }}">@lang(sprintf('%s/app.index_page', Config::get('app_settings.theme')))</a>
					</td>
					<td class="Tools-serach">
						<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput2').value, {{ $id }});return false;">
						<table class="Tools-serach-table"><tr><td class="Tools-serach-input-td">	
							<input id="searchContentInput2" value="@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))'; this.className='empty-search-item'}" />
						</td><td class="Tools-serach-submit-td">
							<input type="submit" value="" class="dofindButton">
						</td></tr></table>
						</form>
					</td>
				</tr>
			</table>
            </td>
			</tr>
        </table>
	</div>
</div>
@stop

@section('javascript')
	@parent
	
	<script type="text/javascript">
		var cumulativeOffset = function(element) {
			var top = 0, left = 0;
			do {
				top += element.offsetTop  || 0;
				left += element.offsetLeft || 0;
				element = element.offsetParent;
			} while(element);

			return {
				top: top,
				left: left
			};
		};
		
		var hilights = document.querySelectorAll('.hilight');
		
		if (hilights.length) {
			var target = hilights[0];
			window.scrollTo(0,cumulativeOffset(target).top-100);
		}
	</script>
	
@stop

@section('title')
	@parent
	
	{{ ' - ' . $bookName . ' - ' . $authorName }}
@stop