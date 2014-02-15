
{*---------------------------------------------------------------------------*}

<div class="research-tiny" data-type='research' data-id={$research.research_id}>
	<div class="resleft">
	{if isset($research.image_filename)}
		<a href='{gl url="research/{$research.research_id}"}'><img class="thumbnail" src='{gl url="files/research/thumb/{$research.image_filename}"}'></a>
	{else}
		<a href='{gl url="research/{$research.research_id}"}'><img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/></a>
	{/if}
	</div>
	<div>
		<a href='{gl url="research/{$research.research_id}"}' class="proj">{$research.research_title}</a><br/>
	</div>
	<div class="cb"></div>
</div>

{*---------------------------------------------------------------------------*}
