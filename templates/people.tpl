
{*---------------------------------------------------------------------------*}
<!--search box-->
<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="people"}'>
	<div id="people-container" class="field f_100">
		<label for="people">
			Find people
		</label>
		<input class="find" autocomplete=off type="text" name="people" placeholder="search"/>
	</div>
</form>
<h2>{t s=People m=0}</h2>
<img id="peoplephoto" src="{$weburl}static/images/people2012.jpg"/>
{if isset($people)}
	<div class="people-teaser-list">

		{include "templates/snippets/section_title.tpl" title={t s=faculty m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'faculty'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=adjunct_faculty m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'adjunct_faculty'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=researcher m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'researcher'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=graduate_student m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'graduate_student'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=undergraduate_student m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'undergraduate_student'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=alumni m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'alumni'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}

		{include "templates/snippets/section_title.tpl" title={t s=recent_visitor m=0}}
		{for $i=0; $i < $people.count; $i++}
			{assign var=ppl value=$people.rows[$i]}
			{if $ppl.people_group == 'recent_visitor'}
				{include "templates/snippets/people_teaser.tpl" people=$ppl}
			{/if}
		{/for}
	</div>
{/if}

{*---------------------------------------------------------------------------*}
