
{*---------------------------------------------------------------------------*}

<div class="pubteaser">
	<div class="publeft">
		{if isset($val.doc_filename)}
			<a href='{gl url="files/publication/{$val.doc_filename}"}'>
			<img class="docimage" src='{gl url="static/images/pdf.png"}'/>
			</a>
		{/if}
		{if isset($val.video_filename)}
			<a href='{gl url="files/publication/{$val.video_filename}"}'>
			{if {$val.video|substr:0:-3} == 'mov'}
				<img src='{gl url="static/images/mov.png"}' class="movie"/>
			{else if {$val.video|substr:0:-3} != 'mov'}
				<img src='{gl url="static/images/wmv.png"}' class="movie"/>
			{/if}
			</a>
		{/if}
	</div>
	<div>
		<a href='{gl url="publications/{$val.publication_id}"}' class="pub">{$val.publication_title}</a>
		<br/>
		{include "templates/snippets/citation.tpl" publication=$val}
	</div>
	<div class="cb"></div>
</div>

{*---------------------------------------------------------------------------*}
