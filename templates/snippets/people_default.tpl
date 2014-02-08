
{*---------------------------------------------------------------------------*}

{if isset($people)}
	<h2>{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}
		<span class="headeraffiliation">{$people.people_affiliation}</span>
	</h2>

	<div class="profileleft">
	{if isset($people.image) and $people.image.count > 0}
		<img src="{$weburl}files/people/{$people.image.rows[0].image_filename}" class="imagewrap" align="left" />
	{/if}
	</div>

	<div class="profileright">
		<p>{$people.people_bio}</p>
		<img src="{txt2img text={$people.people_email}}" />
		
		{if isset($people.image) and $people.image.count > 1}
			{for $i = 1; $i < $people.image.count; $i++}
				<img src="{$weburl}files/people/{$people.image.rows[$i].image_filename}" class="imagewrap" align="left" />
			{/for}
		{/if}

		{if isset($people.research) and $people.research.count > 0}
			{include "templates/snippets/section_title.tpl" title={t s=Projects m=0}}
			{include "templates/snippets/research_list.tpl" research=$people.research}
		{/if}
		{if isset($people.publication) and $people.publication.count > 0}
			{include "templates/snippets/section_title.tpl" title={t s=Publications m=0}}
			{include "templates/snippets/publication_list.tpl" publication=$people.publication}
		{/if}
	</div>
{/if}

{*---------------------------------------------------------------------------*}
