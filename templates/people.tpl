
{*---------------------------------------------------------------------------*}

<h2>{t s=People m=0}</h2>
<img id="peoplephoto" src="{$weburl}files/etc/people2012.jpg"/>
{if isset($people)}
	{include "templates/snippets/people_list.tpl" people=$people group=true}
{/if}

{*---------------------------------------------------------------------------*}
