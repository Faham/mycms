
{*---------------------------------------------------------------------------*}

<a href='{gl url="research/{$research.research_id}"}' class="research-teaser teaser" data-type='research' data-id={$research.research_id}>
	<div class="left">
	{if isset($research.image_filename)}
		<img class="thumbnail" src='{gl url="files/research/image/thumb/{$research.image_filename}"}'>
	{else}
		<img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/>
	{/if}
	</div>
	<div class="textside">
		<div class="title">{$research.research_title}</div>
		<div class="summary">{$research.research_summary}</div>
	</div>
</a>

{*---------------------------------------------------------------------------*}
