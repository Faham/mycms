
{*---------------------------------------------------------------------------*}

<div class="research-teaser" data-type='research' data-id={$research.research_id}>
	<div class="left">
	{if isset($research.image_filename)}
		<a href='{gl url="research/{$research.research_id}"}'><img class="thumbnail" src='{gl url="files/research/image/thumb/{$research.image_filename}"}'></a>
	{else}
		<a href='{gl url="research/{$research.research_id}"}'><img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/></a>
	{/if}
	</div>
	<div>
		<a href='{gl url="research/{$research.research_id}"}' class="proj">{$research.research_title}</a><br/>
		{$research.research_summary}
	</div>
	<div class="cb"></div>
</div>

{*---------------------------------------------------------------------------*}
