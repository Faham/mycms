
{*---------------------------------------------------------------------------*}

<div class="video-list">
<ul>
{if isset($video) and isset($content)}
	{for $i=0; $i < $video.count; $i++}
		{assign var=vid value=$video.rows[$i]}
		<a id="video-{$i}" href='{gl url="files/{$content}/video/{$vid.video_filename}"}'>
		{if {$publication.video|substr:0:-3} == 'mov'}
			<li>
				<img src='{gl url="static/images/mov.png"}' class="movie"/>
				<p><a class="remove" href='{gl url="admin/{$content}/removeVideo"}/{$vid.video_id}--{$contentId}'><font size="2">remove video</font></a></p>
			</li>
		{else if {$publication.video|substr:0:-3} != 'mov'}
			<li>
				<img src='{gl url="static/images/wmv.png"}' class="movie"/>
				<p><a class="remove" href='{gl url="admin/{$content}/removeVideo"}/{$vid.video_id}--{$contentId}'><font size="2">remove video</font></a></p>
			</li>
		{/if}
		</a>
	{/for}
{/if}
</ul>
</div>

{*---------------------------------------------------------------------------*}
