
{*---------------------------------------------------------------------------*}
<!--search box-->
<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="research"}'>
	<div id="research-container" class="field f_100">
		<label for="Research">
			Find Research
		</label>
		<input class="find" autocomplete=off type="text" name="research" placeholder="search"/>
	</div>
</form>
{if isset($research)}
	<h2>{t s='Research' m=0}</h2>
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
