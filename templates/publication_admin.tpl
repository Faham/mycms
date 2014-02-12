
{*---------------------------------------------------------------------------*}

{if isset($publication_list)}
	<h2>{t s=publication m=0}</h2>
	<table cellpadding='0' cellspacing='0' class='persontable'10>
	{for $i=0; $i < $publication_list.count; $i++}
		{assign var=rch value=$publication_list.rows[$i]}
		<tr>
		<td>{$rch->publication_title}</td>
		<td><a href='{gl url="admin/publication/view/{$rch->publication_id}"}' class="edit">edit</a></td>
		<td><a href='{gl url="admin/publication/remove/{$rch->publication_id}"}' class="remove">remove</a></td>
		</tr>
	{/for}
	</table>
{/if}


{*---------------------------------------------------------------------------*}
