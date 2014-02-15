
{*---------------------------------------------------------------------------*}

<div class="image-thumb-list">
{if isset($image)}
	{for $i=0; $i < $image.count; $i++}
		{assign var=img value=$image.rows[$i]}
		<img id="image-{$i}" src="{$weburl}files/research/image/thumb/{$img.image_filename}"/>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
