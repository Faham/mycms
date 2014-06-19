
{*---------------------------------------------------------------------------*}

<h2>{t s=People m=0}</h2>
<img id="peoplephoto" src="{$weburl}static/images/people2012.jpg"/>
{if isset($people)}
	<div class="people-teaser-list">
		{assign var=curgrp value=-1}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}

			{if $ppl.people_group == 'other'}
				{continue}
			{/if}

			{if $ppl.people_group != $curgrp}
				{include "templates/snippets/section_title.tpl" title={t s=$ppl.people_group m=0}}
				{$curgrp = $ppl.people_group}
			{/if}
			{include "templates/snippets/people_teaser.tpl" people=$ppl}
		{/for}
	</div>
{/if}

{*---------------------------------------------------------------------------*}
