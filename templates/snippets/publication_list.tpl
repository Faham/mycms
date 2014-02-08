
{*---------------------------------------------------------------------------*}

{for $i=0; $i < $publication.count; $i++}
	{include "templates/snippets/publication_teaser.tpl" val=$publication.rows[$i]}
{/for}

{*---------------------------------------------------------------------------*}
