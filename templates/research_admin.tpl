
{*---------------------------------------------------------------------------*}

{if isset($research)}
	<h2>{t s=Research m=0}</h2>
	<table cellpadding='0' cellspacing='0' class='persontable'10>
	{for $i=0; $i < $research.count; $i++}
		{assign var=rch value=$research.rows[$i]}
		<tr>
		<td>{$rch->research_title}</td>
		<td><a href='{gl url="admin/research/edit/{$rch->research_id}"}' class="edit">edit</a></td>
		<td><a href='{gl url="admin/research/delete/{$rch->research_id}"}' class="delete">delete</a></td>
		</tr>
	{/for}
	</table>
{/if}


{*---------------------------------------------------------------------------*}
