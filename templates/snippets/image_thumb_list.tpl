
{*---------------------------------------------------------------------------*}

<div class="image-thumb-list">
<ul>
{if isset($image) and isset($content) and isset($contentId)}
	{for $i=0; $i < $image.count; $i++}
		{assign var=img value=$image.rows[$i]}
		<li>
			<img id="image-{$i}" src="{$weburl}files/{$content}/image/thumb/{$img.image_filename}"/>
			<p><a class="remove" href='{gl url="admin/{$content}/removeImage"}/{$img.image_id}--{$contentId}'><font size="2">remove image</font></a></p>
		</li>
	{/for}
{/if}
</ul>
</div>

{*---------------------------------------------------------------------------*}
