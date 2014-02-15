
{*---------------------------------------------------------------------------*}

<div class="people-tiny" data-type='people' data-id={$people.people_id}>
	<div class="left">
		{if isset($people.image_filename)}
			<a href='{gl url="people/{$people.people_id}"}'><img class="thumbnail" src='{gl url="files/people/image/thumb/{$people.image_filename}"}'/></a>
		{else}
			<a href='{gl url="people/{$people.people_id}"}'><img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/></a>
		{/if}
	</div>
	<div class="textside">
	<a href='{gl url="people/{$people.people_id}"}' class="person">{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}</a>
	</div>
</div>

{*---------------------------------------------------------------------------*}
