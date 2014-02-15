
{*---------------------------------------------------------------------------*}

<div class="publication-default" data-type='publication' data-id={$publication.publication_id}>
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

	{include "templates/snippets/section_title.tpl" title={t s=Participants m=0}}
	<div class="persontable">
		{include "templates/snippets/people_list.tpl" people=$publication.people group=false}
	</div>

	{include "templates/snippets/section_title.tpl" title={t s=Citation m=0}}
	{include "templates/snippets/citation.tpl" publication=$publication}

	{include "templates/snippets/section_title.tpl" title=BibTeX}
	{include "templates/snippets/bibtex.tpl" publication=$publication}
</div>

{*---------------------------------------------------------------------------*}
