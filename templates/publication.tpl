
{*---------------------------------------------------------------------------*}
<!--search box-->
<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="publications"}'>
	<div id="publication-container" class="field f_100">
		<label for="publication">
			Find publication
		</label>
		<input class="find" autocomplete=off type="text" name="publication" placeholder="search"/>
	</div>
</form>
<h2>{t s=Publications m=0}</h2>
{if isset($publications)}
	{include "templates/snippets/publication_teaser_list.tpl" publication=$publications group=true}
{/if}

{*---------------------------------------------------------------------------*}
