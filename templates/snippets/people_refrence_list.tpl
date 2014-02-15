
{*---------------------------------------------------------------------------*}

<div class="people-refrence-list">
{for $i=0; $i < $people.count; $i++}
	{assign var=ppl value=$people.rows[$i]}
	{include "templates/snippets/people_teaser.tpl" people=$ppl}
{/for}
</div>

{*---------------------------------------------------------------------------*}
