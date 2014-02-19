
{*---------------------------------------------------------------------------*}

<div class="video-list">
{if isset($video) and isset($content)}
	{for $i=0; $i < $video.count; $i++}
		{assign var=vid value=$video.rows[$i]}
		<a id="video-{$i}" href='{gl url="files/{$content}/video/{$vid.video_filename}"}'>
		{if {$publication.video|substr:0:-3} == 'mov'}
			<img src='{gl url="static/images/mov.png"}' class="movie"/>
		{else if {$publication.video|substr:0:-3} != 'mov'}
			<img src='{gl url="static/images/wmv.png"}' class="movie"/>
		{/if}
		</a>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
