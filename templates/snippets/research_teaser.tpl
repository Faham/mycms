
{*---------------------------------------------------------------------------*}

<div class="resteaser">
	<div class="resleft">
	{if isset($val.image_filename)}
		<a href='{gl url="research/{$val.research_id}"}'><img class="thumbnail" src='{gl url="files/research/thumb/{$val.image_filename}"}'></a>
	{else}
		<a href='{gl url="research/{$val.research_id}"}'><img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/></a>
	{/if}
	</div>
	<div>
		<a href='{gl url="research/{$val.research_id}"}' class="proj">{$val.research_title}</a><br/>
		{$val.research_summary}
	</div>
	<div class="cb"></div>
</div>

{*---------------------------------------------------------------------------*}
