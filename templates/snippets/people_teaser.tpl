
{*---------------------------------------------------------------------------*}

<td width="43">
	{if isset($val.image_filename)}
		<a href='{gl url="people/{$val.people_id}"}'><img class="thumbnail" src='{gl url="files/people/image/thumb/{$val.image_filename}"}'/></a>
	{else}
		<a href='{gl url="people/{$val.people_id}"}'><img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/></a>
	{/if}
</td>
<td width="45%" class="textside">
<a href='{gl url="people/{$val.people_id}"}' class="person">{$val.people_firstname} {$val.people_middlename} {$val.people_lastname}</a>
{if isset($val.people_affiliation)}
	<br/><span class="affiliation">{$val.people_affiliation}</span>
{/if}
</td>

{*---------------------------------------------------------------------------*}
