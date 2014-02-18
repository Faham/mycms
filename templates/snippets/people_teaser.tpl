
{*---------------------------------------------------------------------------*}

<a href='{gl url="people/{$people.people_id}"}' class="people-teaser teaser" data-type='people' data-id={$people.people_id}>
	<div class="left">
		{if isset($people.image_filename)}
			<img class="thumbnail" src='{gl url="files/people/image/thumb/{$people.image_filename}"}'/>
		{else}
			<img class="thumbnail" src='{gl url="static/images/noimage-thumb.gif"}'/>
		{/if}
	</div>
	<div class="textside">
		<div class="title">{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}</div>
		{if isset($people.people_affiliation)}
			<div class="affiliation">{$people.people_affiliation}</div>
		{/if}
	</div>
</a>

{*---------------------------------------------------------------------------*}
