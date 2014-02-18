
{*---------------------------------------------------------------------------*}

<div class="research-refrence-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/snippets/research_teaser.tpl" research=$research.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
