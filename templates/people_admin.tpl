
<h2>Add a Person</h2>
<form method="post" action="people.php" enctype="multipart/form-data">
<table cellspacing="10" width="641">
<tr>
	<td class="prompt">First Name:</td>
	<td><input type="text" class="text" name="FIRSTNAME" value="" id="firstText" /></td>
</tr>
<tr>
	<td class="prompt">Middle Initial:</td>
	<td><input type="text" class="text" name="MIDDLEINITIAL" value="" /></td>
</tr>
<tr>
	<td class="prompt">Last Name:</td>
	<td><input type="text" class="text" name="LASTNAME" value="" /></td>
</tr>
<tr>
	<td class="prompt">Affiliation:</td>
	<td><input type="text" class="text" name="AFFILIATION" value="" /></td>
</tr>
<tr>
	<td class="prompt">Email:</td>
	<td><input type="text" class="text" name="EMAIL" value="" /></td>
</tr>
<tr>
	<td class="prompt">Bio:</td>
	<td>
	<textarea rows="8" name="BIO"></textarea>
	</td>
</tr>
<tr>
	<td class="prompt">Group:</td>
	<td><select name="GROUP" style="min-width: 50%">
	<option value=""></option>
	<option value="1">Faculty</option><option value="2">Adjunct Faculty</option><option value="3">Researchers</option><option value="4">Graduate Students</option><option value="5">Staff</option><option value="6">Alumni</option><option value="7">Recent visitors</option><option value="8">Undergraduate Students</option>	</select>
	</td>
</tr>
<tr>
	<td class="prompt">Image:</td>
	<td><table cellspacing="0" cellpadding="0"><tr><td style="padding-top: 1px"><input type="file" name="IMAGE" /><span class="smalltext"> (.jpg, .gif, or .png)</span></td></tr></table></td>
</tr>
<tr>
	<td></td>
	<td>
	<input type="hidden" name="SAVEEDIT" value="" />
	<input type="submit" value="Submit" />
	</td>
</tr>
</table>
</form>


{*---------------------------------------------------------------------------*}

{if isset($people)}
	<h2>{t s=People m=0}</h2>
	<table cellpadding='0' cellspacing='0' class='persontable'10>
	{for $i=0; $i < $people.count; $i++}
		{assign var=ppl value=$people.rows[$i]}
		<tr>
		<td>{$ppl->people_firstname} {$ppl->people_middlename} {$ppl->people_lastname}</td>
		<td><a href='{gl url="admin/people/edit/{$ppl->people_id}"}' class="edit">edit</a></td>
		<td><a href='{gl url="admin/people/delete/{$ppl->people_id}"}' class="delete">delete</a></td>
		</tr>
	{/for}
	</table>
{/if}

{*---------------------------------------------------------------------------*}

<script type="text/javascript">

publicationModeChange();
projectModeChange();

</script>
