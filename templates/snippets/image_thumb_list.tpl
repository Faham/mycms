
{*---------------------------------------------------------------------------*}

<div class="image-thumb-list">
<ul>
{if isset($image) and isset($content)}
	{for $i=0; $i < $image.count; $i++}
		{assign var=img value=$image.rows[$i]}
		<li>
			<img id="image-{$i}" src="{$weburl}files/{$content}/image/thumb/{$img.image_filename}"/>
			<!--<a class="remove" href='{gl url="admin/{$content}/removeImage"}/{$content.content_id}/{$img.image_filename}'>remove image</a>-->
		</li>
	{/for}
{/if}
</ul>
</div>

{*---------------------------------------------------------------------------*}
