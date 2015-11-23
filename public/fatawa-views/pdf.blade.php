@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="dialog-form">
	<div id="contents">
    <div id="contents_cover" class="Page_showPage">
		<form id="frmPDF" method="post" action="#" onsubmit="validate_pdf_form(document.getElementById('start').value, document.getElementById('end').value);return false;">
			<table class="pdf_form">
            <tr><td class="pdf_logo" rowspan="6">
			<img src="{{ asset('/assets/images/Logo_PDF.jpg') }}" alt="" /></td></tr>
            <tr>
            <td class="pdf_book_Name" colspan="2">@lang('app.pdf_book_name') : {{ $bookName }}</td>
            </tr>
				<tr>
					<td class="pdf_left"><input type="text" size="4" name="start" id="start" /></td>
					<td class="pdf_right">@lang('app.pdf_from_page')</td>
				</tr>
				<tr>
					<td class="pdf_left"><input type="text" size="4" name="end" id="end" /></td>
					<td class="pdf_right">@lang('app.pdf_to_page')</td>
				</tr>
				<tr>
					<td class="pdf_Attention" colspan="2" >@lang('app.pdf_attention')</td>
				</tr>
				<tr>
					<td class="pdf_submit" colspan="2" >
					<input class="pdf_submit_button"  type="submit" value="@lang('app.pdf_save')" name="submit"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
    </div>
</div>
<br />
@stop

@section('javascript')
	@parent
	
	<script type="text/javascript">
		function validate_pdf_form(start, end)
		{
			start = start.replace(/^\s+|\s+$/g, '');
			end = end.replace(/^\s+|\s+$/g, '');
			
			if (start && end && (/\d+/.test(start)) && (/\d+/.test(end)))
			{
				window.location.assign('{{ Config::get('app_settings.pdf_url') }}'+'?url='+encodeURIComponent('{{ url($id.'/'.$volume.'/1') }}')+'&book_name='+encodeURIComponent('{{ $bookName }}')+'&start='+start+'&end='+end+'&submit=true');
			}
			
			return false;
		}
	</script>
@stop

@section('title')
	@parent
@stop