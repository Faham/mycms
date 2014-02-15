
{*---------------------------------------------------------------------------*}

<div class="research-teaser-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/snippets/research_teaser.tpl" research=$research.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
