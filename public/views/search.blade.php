@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>



@section('content')
<div id="contents">
	<div id="contents_cover" class="Page_showPage">
		<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
			
			<tr>
			@comment
				<td class="Tools-serach">
					<\?= form_open('#', array('id' => 'search-query-form', 'onsubmit' => "do_search(document.getElementById('searchContentInput1').value, '".(isset($id) ? $id : '')."');return false;")) ?>
					<table class="Tools-serach-table">
					<tr>
					<td class="Tools-serach-input-td">
					<\?= form_input(array('id' => 'searchContentInput1', 'value' => lang('Default Search'), 'class' => 'empty-search-item', 'onfocus' => "if (this.value == '".lang('Default Search')."') {this.value = ''; this.className=''}", 'onblur' => "if (this.value == '') {this.value = '".lang('Default Search')."'; this.className='empty-search-item'}")); ?>
					</td>
					<td class="Tools-serach-submit-td">
					<\?= form_submit(array('class' => 'dofindButton', 'value' => '')) ?>
					</td>
					</tr>
					</table>
					<\?= form_close() ?>
				</td>	
			@endcomment
			</tr>
                
			<tr>
				<?php
					if ($result_count == 0)
					{
					?>
				<td>	
					<table class="Page-query-noresualt">
					<tr>
					<td>
					<span class="not-found">@lang('app.query_search_result_not_found', array('query' => sprintf('<u><font color="#000000">%s</font></u>', $term)))</span>
					</td>
					</tr>
					</table>
				</td>
					<?php
					goto search;
					}
					else
					{
					?>
				<td>
					<table class="pageSelectorPanel">
						<tr>
							<td class="pg-Left"><span class="result_count">{{ $result_count }}</span>@lang('app.query_search_result')</td>
                            <td class="pg-right">{{ $results->links() }}</td>
						</tr>
					</table>
				</td>
				<?php
					}
				?>       
			</tr>
			

			<tr>
				<td>
					<table id='search-result'>
					
						<?php foreach($results as $key => $result): ?>
						<tr>
							<td class="id">{{ $per_page * ($page - 1) + $key +1 }}</td>
							<td class="data">
								<div class="result">
								<a href="">&nbsp;</a>
							{{-- <\?= anchor($item['address'].'/'.$term, $item['label'], array('title' => '')) ?> --}}
							</div>
						<div class="preview">{{ $result['attrs']['excerpt'] }}</div>
						</td>
						</tr>
						<?php endforeach; ?>
					</table>
				</td>
			</tr>
			
			
		<?php
			if ($result_count > 0)
			{
		?>
			<tr>
				<td>
					<table class="pageSelectorPanel">
						<tr>
							<td class="pg-Left"><span class="result_count">{{ $result_count }}</span>@lang('app.query_search_result')</td>
                            <td class="pg-right">{{ $results->links() }}</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php
			}
search:
		?>
			<tr>
			@comment
				<td class="Tools-serach">
					<\?= form_open('#', array('id' => 'search-query-form', 'onsubmit' => "do_search(document.getElementById('searchContentInput2').value, '".(isset($id) ? $id : '')."');return false;")) ?>
					<table class="Tools-serach-table">
					<tr>
					<td class="Tools-serach-input-td">
					<\?= form_input(array('id' => 'searchContentInput2', 'value' => lang('Default Search'), 'class' => 'empty-search-item', 'onfocus' => "if (this.value == '".lang('Default Search')."') {this.value = ''; this.className=''}", 'onblur' => "if (this.value == '') {this.value = '".lang('Default Search')."'; this.className='empty-search-item'}")); ?>
					</td>
					<td class="Tools-serach-submit-td">
					<\?= form_submit(array('class' => 'dofindButton', 'value' => '')) ?>
					</td>
					</tr>
					</table>
					<\?= form_close() ?>
				</td>	
			@endcomment
			</tr>
		</table>
	</div>
</div>

@comment
<a href="/">Home</a>
	<div class="row">

  <div class="col-lg-offset-36 col-lg-36">
		{{ $results->links() }}
  <p>found in {{ $time*1000 }} miliseconds.</p>
  @foreach ($results as $result)
	<div class="">
		<h4>{{ $result['attrs']['path'] }}</h4>
		<p>
		{{ $result['attrs']['excerpt'] }}
		</p>
	</div>
	<hr>
  @endforeach
  
		{{ $results->links() }}
  </div><!-- /.col-lg-6 -->

</div><!-- /.row -->
@endcomment







@comment
<!--        query,blade.php          -->


				<div id="contents">
				<div id="contents_cover" class="Page_showPage">
				<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
				<tr>	
				<td class="Tools-serach">

				<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput1').value, '{{ isset($bookID) ? $bookID : '' }}');return false;">
				<table class="Tools-serach-table">
				<tr>
				<td class="Tools-serach-input-td">
				<input id="searchContentInput1" value="@lang('app.default_search')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.default_search')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.default_search')'; this.className='empty-search-item'}">
				</td>
				<td class="Tools-serach-submit-td">
				<input type="submit" value="" class="dofindButton">
				</td>
				</tr>
				</table>
				</form>
				</td>	
				</tr>

				<tr>

				@if ($resultCount == 0)
				<td>	
				<table class="Page-query-noresualt">
				<tr>
				<td>
				<span class="not-found">@lang('app.query_search_result_not_found', array('query' => sprintf('<u><font color="#000000">%s</font></u>', $query)))</span>
				</td>
				</tr>
				</table>
				</td>
				@else
				<td>
				<table class="pageSelectorPanel">
				<tr>
				<td class="pg-Left"><span class="result_count">{{ $resultCount }}</span>@lang('app.query_search_result')</td>
				<td class="pg-right"><\?= $pagination_links ?></td>
				</tr>
				</table>
				</td>
				   
				</tr>

				<tr>
				<td>
				<table id='search-result'>
				<\?php foreach($result_items as $item): ?>
				<tr>
				<td class="id"><\?= $item['index'] ?></td>
				<td class="data">
				<div class="result">
				<\?= anchor($item['address'].'/'.$term, $item['label'], array('title' => '')) ?>
				</div>
				<div class="preview"><\?= $item['preview'] ?></div>
				</td>
				</tr>
				<\?php endforeach; ?>
				</table>
				</td>
				</tr>


				<tr>
				<td>
				<table class="pageSelectorPanel">
				<tr>
				<td class="pg-Left"><span class="result_count">{{ $resultCount }}</span>@lang('app.query_search_result')</td>
				<td class="pg-right"><\?= $pagination_links ?></td>
				</tr>
				</table>
				</td>
				</tr>
				@endif

				<tr>	
				<td class="Tools-serach">
				<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput2').value, '{{ isset($bookID) ? $bookID : '' }}');return false;">
				<table class="Tools-serach-table">
				<tr>
				<td class="Tools-serach-input-td">
				<input id="searchContentInput2" value="@lang('app.default_search')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.default_search')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.default_search')'; this.className='empty-search-item'}">
				</td>
				<td class="Tools-serach-submit-td">
				<input type="submit" value="" class="dofindButton">
				</td>
				</tr>
				</table>
				</form>
				</td>	
				</tr>
				</table>
				</div>


<!--        query,blade.php          -->

@endcomment







@stop
