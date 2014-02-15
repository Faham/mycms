
{*---------------------------------------------------------------------------*}

<div class="image-thumb-list">
{if isset($image) and isset($content)}
	{for $i=0; $i < $image.count; $i++}
		{assign var=img value=$image.rows[$i]}
		<img id="image-{$i}" src="{$weburl}files/{$content}/image/thumb/{$img.image_filename}"/>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
