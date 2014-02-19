
{*---------------------------------------------------------------------------*}

<div class="publication-teaser teaser" data-type='publication' data-id={$publication.publication_id}>
	<div class="publeft">
		{if isset($publication.doc_filename)}
			<a href='{gl url="files/publication/doc/{$publication.doc_filename}"}'>
			<img class="docimage" src='{gl url="static/images/pdf.png"}'/>
			</a>
		{/if}
		{if isset($publication.video_filename)}
			<a href='{gl url="files/publication/video/{$publication.video_filename}"}'>
			{if {$publication.video|substr:0:-3} == 'mov'}
				<img src='{gl url="static/images/mov.png"}' class="movie"/>
			{else if {$publication.video|substr:0:-3} != 'mov'}
				<img src='{gl url="static/images/wmv.png"}' class="movie"/>
			{/if}
			</a>
		{/if}
	</div>
	<a href='{gl url="publications/{$publication.publication_id}"}' class='textside'>
		<div class="title">{$publication.publication_title}</div>
		{include "templates/snippets/citation.tpl" publication=$publication}
	</a>
</div>

{*---------------------------------------------------------------------------*}
