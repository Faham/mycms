
{*---------------------------------------------------------------------------*}

<div id="imagedisplay">
	{if isset($imglist)}
		{for $i=0; $i<$imglist.count and $i<48 ; $i++}
			{assign var=itm value=$imglist.rows[$i]}
			<a href='{gl url="{$itm.type}/{$itm.id}"}' title="{$itm.title}">
			<img class="thumbnail" src='{gl url="files/{$itm.type}/thumb/{$itm.image}"}'/>
			</a>
		{/for}
	{/if}
</div>

{*---------------------------------------------------------------------------*}

<p id="hci_description">{t s="HCI_DESCRIPTION" m=0}</p>

{include "templates/snippets/section_title.tpl" title={t s=Faculty m=0}}
{if isset($faculty)}
	{include "templates/snippets/people_list.tpl" people=$faculty group=false}
{/if}

{*---------------------------------------------------------------------------*}

{include "templates/snippets/section_title.tpl" title={t s='Current Research' m=0}}
{if isset($research)}
	{include "templates/snippets/research_list.tpl" research=$research}
{/if}
<a href="{gl url='research'}">{t s='View All' m=0}<span class="arrows">&gt;&gt;</span></a>

{*---------------------------------------------------------------------------*}

{include "templates/snippets/section_title.tpl" title={t s='Recent Publications' m=0}}
{if isset($publication)}
	{include "templates/snippets/publication_list.tpl" publication=$publication}
{/if}
<a href="{gl url='publications'}">{t s='View All' m=0}<span class="arrows">&gt;&gt;</span></a>

{*---------------------------------------------------------------------------*}
