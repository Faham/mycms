
{*---------------------------------------------------------------------------*}

{if $error}
<div class="error">
	<ul class="response">
	{foreach from=$error item=e}
		<li class="{$e.level}"><div>{$e.msg}</div></li>
	{/foreach}
	</ul>
</div>
{/if}

{*---------------------------------------------------------------------------*}
