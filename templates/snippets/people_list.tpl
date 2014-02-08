
{*---------------------------------------------------------------------------*}

{assign var=left value=true}
{if not $group}
	<table cellpadding='0' cellspacing='0' class='persontable'10>
{else}
	{assign var=curgrp value=-1}
{/if}
{for $i=0; $i < $people.count; $i++}
	{assign var=ppl value=$people.rows[$i]}
	{if $group and $ppl.people_group != $curgrp}
		{include "templates/snippets/section_title.tpl" title={t s=$ppl.people_group m=0}}
		{$curgrp = $ppl.people_group}
		<table cellpadding='0' cellspacing='0' class='persontable'10>
	{/if}
	
	{if $left}<tr>{/if}
	{include "templates/snippets/people_teaser.tpl" val=$ppl}
	{if not $left}</tr>{/if}
			
	{if {$i+1} == $people.count or {$group and $people.rows[$i+1].people_group != $curgrp}}
		{if $left}</tr>{/if}
		</table>
		{$left = true}
	{else}
		{$left = {not $left}}
	{/if}
{/for}

{*---------------------------------------------------------------------------*}
