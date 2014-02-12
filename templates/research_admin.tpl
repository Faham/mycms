
{*---------------------------------------------------------------------------*}

{if isset($research_list)}
	<h2>{t s=Research m=0}</h2>
	<table cellpadding='0' cellspacing='0' class='persontable'10>
	{for $i=0; $i < $research_list.count; $i++}
		{assign var=rch value=$research_list.rows[$i]}
		<tr>
		<td>{$rch->research_title}</td>
		<td><a href='{gl url="admin/research/view/{$rch->research_id}"}' class="edit">edit</a></td>
		<td><a href='{gl url="admin/research/remove/{$rch->research_id}"}' class="remove">remove</a></td>
		</tr>
	{/for}
	</table>
{/if}


{*---------------------------------------------------------------------------*}
