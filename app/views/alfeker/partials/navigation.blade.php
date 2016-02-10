<div class="righthead">
الفهرس
</div>

<div class="vernav">
@if (isset($tabs) and is_array($tabs))
	@foreach ($tabs as $tabcontainer)

		<ul>
			@foreach ($tabcontainer as $tab)
			<li {{ ($tab['selected'] === TRUE) ? 'class="selected-tab"' : '' }}>
				<span>{{ Helpers::link_to($tab['path'], $tab['group']) }}</span>
			</li>
			@endforeach
		</ul>

	@endforeach
@endif
</div>