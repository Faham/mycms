
{*---------------------------------------------------------------------------*}

<h2>{t s='Research' m=0}</h2>
{if isset($research)}
	{for $i=0; $i < $research.count; $i++}
		{if $research.rows[$i].research_status != 'past'}
			{include "templates/snippets/research_teaser.tpl" research=$research.rows[$i]}
		{/if}
	{/for}

	{include "templates/snippets/section_title.tpl" title={t s='Past Projects' m=0}}
	{for $i=0; $i < $research.count; $i++}
		{if $research.rows[$i].research_status == 'past'}
			{include "templates/snippets/research_teaser.tpl" research=$research.rows[$i]}
		{/if}
	{/for}
{/if}


{*---------------------------------------------------------------------------*}
