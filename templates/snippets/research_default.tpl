
{*---------------------------------------------------------------------------*}

<div class="resdefault">
	<h2>
		<span style="color: gray">{t s=Project m=0}:</span>
		{$research.research_title}
	</h2>

	<p>{$research.research_description}</p>

	{if isset($research.image) and $research.image.count > 0}
		{for $i = 0; $i < $research.image.count; $i++}
			<img src="{gl url="files/research/{$research.image.rows[$i].image_filename}"}" class="image" />
		{/for}
	{/if}
	
	{if isset($research.video) and $research.video.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Downloads m=0}}	
		{for $i = 0; $i < $research.video.count; $i++}
			<div class="download">
			<a class="{if {$research.video.rows[$i].video_filename|substr:0:-3} == 'mov'}mov{else}wmv{/if}" 
				href='{gl url="files/research/{$research.video.rows[$i].video_filename}"}'>
			Video
			</a>
			</div>
		{/for}
	{/if}	

	{if isset($research.people) and $research.people.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Participants m=0}}
		<div class="persontable">
			{include "templates/snippets/people_list.tpl" people=$research.people group=false}
		</div>
	{/if}
</div>

{*---------------------------------------------------------------------------*}