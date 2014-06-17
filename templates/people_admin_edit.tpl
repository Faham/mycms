
{*---------------------------------------------------------------------------*}

<img id='remove-refrence-button' src='{gl url="static/images/recyclebin.png"}'/>

{*---------------------------------------------------------------------------*}

<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/people/create"}'>
	<div id="people-container" class="field f_100">
		<label for="people">
			Find people
		</label>
		<input class="find" autocomplete=off type="text" name="people" placeholder="search"/>
	</div>
</form>

{*---------------------------------------------------------------------------*}

{if isset($people)}
	{include "templates/snippets/section_title.tpl" title={t s='Edit people' m=0}}

<div class="TTWForm-container"
			data-type='people'
			data-id='{$people.people_id}'
>

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
			action='{gl url="admin/people/edit"}/{$people.people_id}'>

		<a class="remove" href='{gl url="admin/people/remove"}/{$people.people_id}'>remove</a>

		<div id="people_firstname-container" class="field f_100">
			<label for="people_firstname">
				First Name
			</label>
			<input type="text" name="people_firstname" id="people_firstname" required pattern="[\u00C0-\u1FFF\u2C00-\uD7FF\w\s]+"
				value="{$people.people_firstname}">
		</div>

		<div id="people_middlename-container" class="field f_100">
			<label for="people_middlename">
				Middle Name
			</label>
			<input type="text" name="people_middlename" id="people_middlename" pattern="[\u00C0-\u1FFF\u2C00-\uD7FF\w\s]*"
				value="{$people.people_middlename}">
		</div>

		<div id="people_lastname-container" class="field f_100">
			<label for="people_lastname">
				Last Name
			</label>
			<input type="text" name="people_lastname" id="people_lastname" required pattern="[\u00C0-\u1FFF\u2C00-\uD7FF\w\s]+"
				value="{$people.people_lastname}">
		</div>

		<div id="people_affiliation-container" class="field f_100">
			<label for="people_affiliation">
				Affiliation
			</label>
			<input type="text" name="people_affiliation" id="people_affiliation"
				value="{$people.people_affiliation}">
		</div>

		<div id="people_email-container" class="field f_100">
			<label for="people_email">
				Email Address
			</label>
			<input type="email" name="people_email" id="people_email"
				value="{$people.people_email}">
		</div>

		<div id="people_nsid-container" class="field f_100">
			<label for="people_nsid">
				NSID
			</label>
			<input type="text" name="people_nsid" id="people_nsid"
				value="{$people.people_nsid}" pattern="[a-zA-Z][a-zA-Z][a-zA-Z][0-9][0-9][0-9]">
				<!-- I don't know why pattern="[a-zA-Z]{3}[0-9]{3} doesn't work" -->
		</div>

		<div id="people_group-container" class="field f_100">
			<label for="people_group">
				Group
			</label>

			<select name="people_group" id="people_group" required>
				<option id="people_group-1" value="faculty"
				{if "faculty" == $people.people_group}
					selected
				{/if}
				>
					{t s='faculty' m=0}
				</option>
				<option id="people_group-2" value="adjunct_faculty"
				{if "adjunct_faculty" == $people.people_group}
					selected
				{/if}
				>
					{t s='adjunct_faculty' m=0}
				</option>
				<option id="people_group-3" value="researcher"
				{if "researcher" == $people.people_group}
					selected
				{/if}
				>
					{t s='researcher' m=0}
				</option>
				<option id="people_group-4" value="graduate_student"
				{if "graduate_student" == $people.people_group}
					selected
				{/if}
				>
					{t s='graduate_student' m=0}
				</option>
				<option id="people_group-5" value="undergraduate_student"
				{if "undergraduate_student" == $people.people_group}
					selected
				{/if}
				>
					{t s='undergraduate_student' m=0}
				</option>
				<option id="people_group-6" value="alumni"
				{if "alumni" == $people.people_group}
					selected
				{/if}
				>
					{t s='alumni' m=0}
				</option>
				<option id="people_group-7" value="recent_visitor"
				{if "recent_visitor" == $people.people_group}
					selected
				{/if}
				>
					{t s='recent_visitor' m=0}
				</option>
				<option id="people_group-8" value="other"
				{if "other" == $people.people_group}
					selected
				{/if}
				>
					{t s='other' m=0}
				</option>
			</select>

		</div>

		<div id="people_role-container" class="field f_100">
			<label for="people_role">
				Role
			</label>

			<select name="people_role" id="people_role" required>
				<option id="people_role-1" value="authenticated"
				{if "authenticated" == $people.people_role}
					selected
				{/if}
				>
					{t s='authenticated' m=0}
				</option>
				<option id="people_role-2" value="administrator"
				{if "administrator" == $people.people_role}
					selected
				{/if}
				>
					{t s='administrator' m=0}
				</option>
			</select>

		</div>

		<div id="people_start-container" class="field f_100">
			<label for="people_start">
				Start Date
			</label>
			<input class="ttw-date date" id="people_start" maxlength="524288" name="people_start"
			size="20" tabindex="0" title=""
				value="{$people.people_start}">
		</div>

		<div id="people_end-container" class="field f_100">
			<label for="people_end">
				End Date
			</label>
			<input class="ttw-date date" id="people_end" maxlength="524288" name="people_end"
			size="20" tabindex="0" title=""
				value="{$people.people_end}">
		</div>

		<div id="people_bio-container" class="field f_100">
			<label for="people_bio">
				Bio
			</label>
			<textarea name="people_bio" id="people_bio">{$people.people_bio}</textarea>
		</div>

		<div id="image-container" class="field f_100">
			<label for="image" class="label">
				Upload image<div class="smalltext">(.jpg, .gif, or .png)</div>
			</label>
			<div class="original_div">
				<input type="file" name="image[]" accept="image/*"/>
				<a class="addImage"><font size="2">Add More Images</font></a>
			</div>
			<div class="addtionalImages">
				<ol class="image_list">
					
				</ol>
			</div>
			{include "templates/snippets/image_thumb_list.tpl" image=$people.image content='people' contentId=$people.people_id}
			<!--remove image link-->
			<a class="remove" href='{gl url="admin/people/removeAllImages"}/{$people.people_id}'>remove all images</a>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Save">
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='Research' m=0}}
		<div id="research-container" class="field f_100">
			<label for="research">
				Add research
			</label>
			<input class="refrence" autocomplete=off type="text" name="research" placeholder="search"/>
			<div class="removable-refrence-list">
				{include "templates/snippets/research_refrence_list.tpl" research=$people.research}
			</div>
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='Publication' m=0}}
		<div id="publication-container" class="field f_100">
			<label for="publication">
				Add publication
			</label>
			<input class="refrence" autocomplete=off type="text" name="publication" placeholder="search"/>
			<div class="removable-refrence-list">
				{include "templates/snippets/publication_refrence_list.tpl" publication=$people.publication}
			</div>
		</div>
	</form>
</div>

<br clear="all" />

{/if}

{*---------------------------------------------------------------------------*}
