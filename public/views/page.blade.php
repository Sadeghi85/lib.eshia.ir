@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
	<div id="contents_cover" class="Page_showPage">
		<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
		<tr>	
            <td>
            <table class="Tools-Table" align="right">
				<tr>
					<td class="Tools-pdf">
						<a href="{{ sprintf('/pdf/%s/%s', $id, $volume) }}" title="@lang('app.save_this_book_to_pdf_format')" target="_blank">@lang('app.pdf_format')</a>
					</td>
					<td class="Tools-certificate">
						<a href="{{ sprintf('/shenasnameh/%s', $id) }}" title="@lang('app.book_certificate')" target="_blank">@lang('app.certificate')</a>
					</td>
                    <td class="Tools-index">
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $indexPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.index_page')) }}" target="_blank">@lang('app.index_page')</a>
					</td>
					<td class="Tools-serach">
						<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput1').value, {{ $id }});return false;">
						<table class="Tools-serach-table"><tr><td class="Tools-serach-input-td">	
							<input id="searchContentInput1" value="@lang('app.search_in_this_book')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.search_in_this_book')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.search_in_this_book')'; this.className='empty-search-item'}" />
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
            	<input type="hidden" name="id" value="{{ $id }}" />
				
				<table align="center" class="BookAccessPanel">
					<tr>
					    
						<td align="center">
						@if ( ! is_null($firstPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.first_page')) }}">&nbsp;&nbsp;&nbsp;««{{ trans('app.first_page') }}&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
						
						
						<td align="center">
						@if ( ! is_null($prevPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $prevPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.prev_page')) }}">«{{ trans('app.prev_page') }}</a>
						@endif
						</td>
						<td align="center">
						<table align="center" class="BookAccessPanel2">
							<tr>
								<td><span class="page-volumeIndex">&nbsp;&nbsp;&nbsp;{{ trans('app.volume') }}</span>
								{{ Form::select('volume', $volumeOptions, $volume, array('class' => 'VolumeSelector')) }}
								</td>
								<td><input class="book-search-submit" type="submit" value="{{ trans('app.goto_page') }}" />
								<input name="page" class="PageSelector" value="{{ $page }}" />
								</td>
							</tr>
						</table></td>
						<td align="center">
						@if ( ! is_null($nextPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $nextPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.next_page')) }}">{{ trans('app.next_page') }}»</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($lastPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $lastPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.last_page')) }}">&nbsp;&nbsp;&nbsp;{{ trans('app.last_page') }}»»&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
                         <td align="center">

                         </td>
                       
					</tr>
				</table>
				</form>
		</td>
        </tr>

        <tr>
        <td class="Book_Title">
        	<span class="current_book_name"><font color="#eebb41">@lang('app.page_book_name')</font>&nbsp;{{ $bookName }}<font color="#eebb41">&nbsp;@lang('app.page_author')</font>&nbsp;{{ Helpers::link_to("/$authorName", $authorName, array('title' => '')) }}</span>
            <span class="current_book_Page"><font color="#eebb41">&nbsp;&nbsp;&nbsp;@lang('app.page_book_volume')</font>&nbsp;{{ $volume }}&nbsp;</span>
            <span class="current_book_Page"><font color="#eebb41">@lang('app.page_book_page')</font>&nbsp;{{ $page }}</span>
        </td>
        </tr>
    
        <tr>
        <td class="book-page-show">
		{{ $content }}
        
        </td>
        </tr>
        
       
        
        <tr>
        <td class="Book_Title">
        	<span class="current_book_name"><font color="#eebb41">@lang('app.page_book_name')</font>&nbsp;{{ $bookName }}<font color="#eebb41">&nbsp;@lang('app.page_author')</font>&nbsp;{{ Helpers::link_to("/$authorName", $authorName, array('title' => '')) }}</span>
            <span class="current_book_Page"><font color="#eebb41">&nbsp;&nbsp;&nbsp;@lang('app.page_book_volume')</font>&nbsp;{{ $volume }}&nbsp;</span>
            <span class="current_book_Page"><font color="#eebb41">@lang('app.page_book_page')</font>&nbsp;{{ $page }}</span>
        </td>
        </tr>
        
        <tr>
            <td>
            	<form method="post" action="{{ Request::url() }}" class="frmQuickAccess">
            	<input type="hidden" name="id" value="{{ $id }}" />
				
				<table align="center" class="BookAccessPanel">
					<tr>
					    
						<td align="center">
						@if ( ! is_null($firstPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $firstPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.first_page')) }}">&nbsp;&nbsp;&nbsp;««{{ trans('app.first_page') }}&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
						
						
						<td align="center">
						@if ( ! is_null($prevPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $prevPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.prev_page')) }}">«{{ trans('app.prev_page') }}</a>
						@endif
						</td>
						<td align="center">
						<table align="center" class="BookAccessPanel2">
							<tr>
								<td><span class="page-volumeIndex">&nbsp;&nbsp;&nbsp;{{ trans('app.volume') }}</span>
								{{ Form::select('volume', $volumeOptions, $volume, array('class' => 'VolumeSelector')) }}
								</td>
								<td><input class="book-search-submit" type="submit" value="{{ trans('app.goto_page') }}" />
								<input name="page" class="PageSelector" value="{{ $page }}" />
								</td>
							</tr>
						</table></td>
						<td align="center">
						@if ( ! is_null($nextPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $nextPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.next_page')) }}">{{ trans('app.next_page') }}»</a>
						@endif
						</td>
						
						<td align="center">
						@if ( ! is_null($lastPage))
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $lastPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.last_page')) }}">&nbsp;&nbsp;&nbsp;{{ trans('app.last_page') }}»»&nbsp;&nbsp;&nbsp;</a>
						@endif
						</td>
                         <td align="center">

                         </td>
                       
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
						<a href="{{ sprintf('/pdf/%s/%s', $id, $volume) }}" title="@lang('app.save_this_book_to_pdf_format')" target="_blank">@lang('app.pdf_format')</a>
					</td>
					<td class="Tools-certificate">
						<a href="{{ sprintf('/shenasnameh/%s', $id) }}" title="@lang('app.book_certificate')" target="_blank">@lang('app.certificate')</a>
					</td>
                    <td class="Tools-index">
						<a href="{{ sprintf('/%s/%s/%s', $id, $volume, $indexPage) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.index_page')) }}" target="_blank">@lang('app.index_page')</a>
					</td>
					<td class="Tools-serach">
						<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput2').value, {{ $id }});return false;">
						<table class="Tools-serach-table"><tr><td class="Tools-serach-input-td">	
							<input id="searchContentInput2" value="@lang('app.search_in_this_book')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.search_in_this_book')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.search_in_this_book')'; this.className='empty-search-item'}" />
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

@section('title')
	@parent
	
	{{ ' - ' . $bookName . ' - ' . $authorName }}
@stop