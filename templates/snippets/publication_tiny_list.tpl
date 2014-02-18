
{*---------------------------------------------------------------------------*}

<div class="publication-tiny-list">
{for $i=0; $i < $publication.count; $i++}
	{include "templates/snippets/publication_tiny.tpl" publication=$publication.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
