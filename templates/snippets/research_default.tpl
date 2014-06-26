
{*---------------------------------------------------------------------------*}

{if isset($research)}
<div class="research-default" data-type='research' data-id={$research.research_id}>
	{if isset($user) and $user.is_admin}
		<a class="edit-node" href='{gl url="admin/research/view"}/{$research.research_id}'>edit</a>
	{/if}

	<h2>
		<span style="color: gray">{t s=Project m=0}:</span>
		{$research.research_title}
	</h2>

	{if isset($research.image) and $research.image.count > 0}
		{for $i = 0; $i < $research.image.count; $i++}
			<img src="{gl url="files/research/image/{$research.image.rows[$i].image_filename}"}" class="image" />
		{/for}
	{/if}

	<p>{$research.research_description}</p>

	{if isset($research.video) and $research.video.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Downloads m=0}}
		{for $i = 0; $i < $research.video.count; $i++}
			<div class="download">
			<a class="{if {$research.video.rows[$i].video_filename|substr:0:-3} == 'mov'}mov{else}wmv{/if}"
				href='{gl url="files/research/video/{$research.video.rows[$i].video_filename}"}'>
			Video
			</a>
			</div>
		{/for}
	{/if}

	{if isset($research.people) and $research.people.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Participants m=0}}
		<div class="persontable">
			{include "templates/snippets/people_teaser_list.tpl" people=$research.people group=false}
		</div>
	{/if}
	{if isset($research.publication) and $research.publication.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Publications m=0}}
		{include "templates/snippets/publication_teaser_list.tpl" publication=$research.publication group=false}
	{/if}
</div>
{/if}

{*---------------------------------------------------------------------------*}
