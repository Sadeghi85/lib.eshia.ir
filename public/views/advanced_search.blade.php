@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
	<div id="contents_cover" class="Page_advancedSearch">
		<table id="advancedsearch">
		<tbody><tr>
		<td>
		<form name="frmQuery" method="post">
		<span class="tdLabel">&nbsp;</span>
		<table id="advanced" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td class="tdLabel">مطالبي را پيدا کن که شامل ...</td>
		<td class="tdInput"></td>
		</tr>
		<tr>
		<td class="tdLabel">همه اين کلمات باشد</td>
		<td class="tdInput"><input name="and" type="text" value=""></td>
		</tr>
		<tr>
		<td class="tdLabel">اين جمله باشد</td>
		<td class="tdInput"><input name="phrase" type="text" value=""></td>
		</tr>
		<tr>
		<td class="tdLabel">هر کدام از اين کلمات باشد</td>
		<td class="tdInput"><input name="or" type="text" value=""></td>
		</tr>
		</tbody>
		</table>
		<span class="tdLabel">اما ...</span>
		<table cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td class="tdLabel">شامل اين کلمات نباشد</td>
		<td class="tdInput"><input name="not" type="text" value=""></td>
		</tr>
		</tbody>
		</table>
	@comment
		<span class="tdLabel">تنظيمات پيشرفته ...</span>
	
		<table cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td class="tdLabel">تعداد نتيجه ها در هر صفحه</td>
		<td class="tdInput">
		<select id="rpp">
		<option selected="selected">5 نتيجه</option>
		<option>10 نتيجه</option>
		<option>20 نتيجه</option>
		<option>50 نتيجه</option>
		<option>100 نتيجه</option>
		<option>همه نتايج</option>
		</select>
		</td>
		</tr>
		</tbody>
		</table>
	@endcomment
		<span class="tdLabel">محدوده جستجو ...</span>
		<table cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td class="tdLabel"><input type="radio" id="groupAll" value="groupAll" name="groupWhere" checked="checked"><label for="groupAll" class="tdLabel">جستجو در همه کتابها</label></td>
		<td class="tdInput"></td>
		</tr>
		<tr>
		<td class="tdLabel"><input type="radio" id="groupOne" value="groupOne" name="groupWhere"><label for="groupOne" class="tdLabel">جستجو در يک گروه</label></td>
		<td class="tdInput">
			<select name="groupKey">
				@foreach ($groupArray as $groupKey => $groupName)
					<option value="{{ $groupKey }}">{{ $groupName }}</option>
				@endforeach
			</select>
		</td>
		</tr>
		</tbody>
		</table>
		<div align="center" style="margin: 0.5em 2em"><input type="submit" value="جستجو"></div>
		</form>
		</td>
		</tr>
		</tbody></table>
	</div>
</div>
@stop

@section('title')
	@parent
@stop