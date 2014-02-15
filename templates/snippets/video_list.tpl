
{*---------------------------------------------------------------------------*}

<div class="video-list">
{if isset($video) and isset($content)}
	{for $i=0; $i < $video.count; $i++}
		{assign var=vid value=$video.rows[$i]}
		<video id="video-{$i}" src="{$weburl}files/{$content}/video/{$vid.image_filename}"></video>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
