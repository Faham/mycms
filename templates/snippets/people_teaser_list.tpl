
{*---------------------------------------------------------------------------*}

<div class="people-teaser-list">
{if not isset($group) or not $group}
	{for $i=0; $i < $people.count; $i++}
		{include "templates/snippets/people_teaser.tpl" people=$people.rows[$i]}
	{/for}
{else}
	{assign var=curgrp value=-1}
	{for $i=0; $i < $people.count; $i++}
		{assign var=ppl value=$people.rows[$i]}
		{if $group and $ppl.people_group != $curgrp}
			{include "templates/snippets/section_title.tpl" title={t s=$ppl.people_group m=0}}
			{$curgrp = $ppl.people_group}
		{/if}
		{include "templates/snippets/people_teaser.tpl" people=$ppl}
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
