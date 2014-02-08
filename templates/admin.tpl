
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

<h2>People</h2>

<table cellspacing="0" cellpadding="2" width="525">
<tr class="pubrow alternate"><td>Carl Gutwin</td><td>Faculty</td><td class="edit"><a href="people.php?edit=2">edit</a></td><td class="delete"><a href="people.php?delete=2">delete</a></td></tr>
</table>

<script type="text/javascript">

publicationModeChange();
projectModeChange();

</script>
