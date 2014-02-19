
{*---------------------------------------------------------------------------*}

<a href='{gl url="publication/{$publication.publication_id}"}' class="publication-tiny tiny"
	data-type='publication'
	data-id={$publication.publication_id}
>
	<div class="left">
		{if isset($publication.image_filename)}
			<img src='{gl url="files/publication/image/thumb/{$publication.image_filename}"}'/>
		{else}
			<img src='{gl url="static/images/noimage-thumb.gif"}'/>
		{/if}
	</div>
	<div class="textside">{$publication.publication_title}</div>
</a>

{*---------------------------------------------------------------------------*}
