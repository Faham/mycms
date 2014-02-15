
{*---------------------------------------------------------------------------*}

<div class="people-tiny-list">
{for $i=0; $i < $people.count; $i++}
	{include "templates/snippets/people_tiny.tpl" people=$people.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
