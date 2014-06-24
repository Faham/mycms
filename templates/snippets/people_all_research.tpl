
{*---------------------------------------------------------------------------*}

<div class="people-default" data-type='people' data-id={$people.people_id}>
{if isset($people)}
	<h2>{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}
		<span class="headeraffiliation">{$people.people_affiliation}</span>
	</h2>

	<div class="profileleft">
	{if isset($people.image) and $people.image.count > 0}
		<img src="{$weburl}files/people/image/{$people.image.rows[0].image_filename}" class="imagewrap" align="left" />
	{/if}
	</div>

	<div class="profileright">
		<a href="{$weburl}people/{$people.people_id}">&lt;&lt; Back to Profile</a>
		{if isset($people.research) and $people.research.count > 0}
			{include "templates/snippets/research_teaser_list.tpl" research=$people.research}
		{/if}
	</div>
{/if}
</div>

{*---------------------------------------------------------------------------*}
