
{*---------------------------------------------------------------------------*}

<a href='{gl url="people/{$people.people_id}"}' class="people-tiny tiny" data-type='people' data-id={$people.people_id}>
	<div class="left">
		{if isset($people.image_filename)}
			<img class="tiny" src='{gl url="files/people/image/thumb/{$people.image_filename}"}'/>
		{else}
			<img class="tiny" src='{gl url="static/images/noimage-thumb.gif"}'/>
		{/if}
	</div>
	<div class="textside">{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}
	</div>
</a>

{*---------------------------------------------------------------------------*}
