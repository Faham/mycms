
{*---------------------------------------------------------------------------*}

{if isset($publication)}
<div class="publication-default" data-type='publication' data-id={$publication.publication_id}>
	{if isset($user) and $user.is_admin}
		<a class="edit-node" href='{gl url="admin/publication/view"}/{$publication.publication_id}'>edit</a>
	{/if}

	<h2>
		<span style="color: gray">{t s=Publication m=0}:</span>
		{$publication.publication_title}
	</h2>

	<p>{$publication.publication_abstract}</p>

	{if isset($publication.image) and $publication.image.count > 0}
		{for $i = 0; $i < $publication.image.count; $i++}
			<img src="{gl url="files/publication/image/{$publication.image.rows[$i].image_filename}"}" class="image" />
		{/for}
	{/if}

	{if {isset($publication.doc) and $publication.doc.count > 0} or {isset($publication.video) and $publication.video.count > 0}}
		{include "templates/snippets/section_title.tpl" title={t s=Downloads m=0}}
		{if isset($publication.doc) and $publication.doc.count > 0}
			{for $i = 0; $i < $publication.doc.count; $i++}
				<div class="download">
					<a class="pdf" href='{gl url="files/publication/doc/{$publication.doc.rows[$i].doc_filename}"}'>
					PDF
					</a>
				</div>
			{/for}
		{/if}
		{if isset($publication.video) and $publication.video.count > 0}
			{for $i = 0; $i < $publication.video.count; $i++}
				<div class="download">
				<a class="{if {$publication.video.rows[$i].video_filename|substr:0:-3} == 'mov'}mov{else}wmv{/if}"
					href='{gl url="files/publication/video/{$publication.video.rows[$i].video_filename}"}'>
				Video
				</a>
				</div>
			{/for}
		{/if}
	{/if}

	{if isset($publication.people) and $publication.people.count > 0}
	{include "templates/snippets/section_title.tpl" title={t s=Participants m=0}}
	<div class="persontable">
		{include "templates/snippets/people_teaser_list.tpl" people=$publication.people group=false}
	</div>
	{/if}

	{if isset($publication.research) and $publication.research.count > 0}
		{include "templates/snippets/section_title.tpl" title={t s=Projects m=0}}
		{include "templates/snippets/research_teaser_list.tpl" research=$publication.research group=false}
	{/if}

	{include "templates/snippets/section_title.tpl" title={t s=Citation m=0}}
	{include "templates/snippets/citation.tpl" publication=$publication}

	{include "templates/snippets/section_title.tpl" title=BibTeX}
	{include "templates/snippets/bibtex.tpl" publication=$publication}
</div>
{/if}

{*---------------------------------------------------------------------------*}
