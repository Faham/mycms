
{*---------------------------------------------------------------------------*}

<div class="publication-refrence-list">
{for $i=0; $i < $publication.count; $i++}
	{include "templates/snippets/publication_teaser.tpl" publication=$publication.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
