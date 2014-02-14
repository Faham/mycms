
{*---------------------------------------------------------------------------*}

{include "templates/snippets/section_title.tpl" title={t s='Add a Person' m=0}}

<div class="TTWForm-container">

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
			{if isset($people)}
			action='{gl url="admin/people/edit"}/{$people->people_id}'
			{else}
			action='{gl url="admin/people/create"}'
			{/if}
	>

		<div id="people_firstname-container" class="field f_100">
			<label for="people_firstname">
				First Name
			</label>
			<input type="text" name="people_firstname" id="people_firstname" required pattern="[a-zA-Zs]+"
				{if isset($people)}
				value="{$people->people_firstname}"
				{/if}
			>
		</div>

		<div id="people_middlename-container" class="field f_100">
			<label for="people_middlename">
				Middle Name
			</label>
			<input type="text" name="people_middlename" id="people_middlename" pattern="[a-zA-Zs]*"
				{if isset($people)}
				value="{$people->people_middlename}"
				{/if}
			>
		</div>

		<div id="people_lastname-container" class="field f_100">
			<label for="people_lastname">
				Last Name
			</label>
			<input type="text" name="people_lastname" id="people_lastname" required pattern="[a-zA-Zs]+"
				{if isset($people)}
				value="{$people->people_lastname}"
				{/if}
			>
		</div>

		<div id="people_affiliation-container" class="field f_100">
			<label for="people_affiliation">
				Affiliation
			</label>
			<input type="text" name="people_affiliation" id="people_affiliation"
				{if isset($people)}
				value="{$people->people_affiliation}"
				{/if}
			>
		</div>

		<div id="people_email-container" class="field f_100">
			<label for="people_email">
				Email Address
			</label>
			<input type="email" name="people_email" id="people_email"
				{if isset($people)}
				value="{$people->people_email}"
				{/if}
			>
		</div>

		<div id="field11-container" class="field f_100">
			<label for="field11">
				Group
			</label>

			<select name="people_group" id="people_group" required>
				<option id="people_group-1" value="faculty"
				{if isset($people) and "faculty" == $people->people_group}
					selected
				{/if}
				>
					{t s='faculty' m=0}
				</option>
				<option id="people_group-2" value="adjunct_faculty"
				{if isset($people) and "adjunct_faculty" == $people->people_group}
					selected
				{/if}
				>
					{t s='adjunct_faculty' m=0}
				</option>
				<option id="people_group-3" value="researcher"
				{if isset($people) and "researcher" == $people->people_group}
					selected
				{/if}
				>
					{t s='researcher' m=0}
				</option>
				<option id="people_group-4" value="graduate_student"
				{if isset($people) and "graduate_student" == $people->people_group}
					selected
				{/if}
				>
					{t s='graduate_student' m=0}
				</option>
				<option id="people_group-5" value="undergraduate_student"
				{if isset($people) and "undergraduate_student" == $people->people_group}
					selected
				{/if}
				>
					{t s='undergraduate_student' m=0}
				</option>
				<option id="people_group-6" value="alumni"
				{if isset($people) and "alumni" == $people->people_group}
					selected
				{/if}
				>
					{t s='alumni' m=0}
				</option>
				<option id="people_group-7" value="recent_visitor"
				{if isset($people) and "recent_visitor" == $people->people_group}
					selected
				{/if}
				>
					{t s='recent_visitor' m=0}
				</option>
			</select>

		</div>

		<div id="people_start-container" class="field f_100">
			<label for="people_start">
				Start Date
			</label>
			<input class="ttw-date date" id="people_start" maxlength="524288" name="people_start"
			size="20" tabindex="0" title=""
				{if isset($people)}
				value="{$people->people_start}"
				{/if}
			>
		</div>

		<div id="people_end-container" class="field f_100">
			<label for="people_end">
				End Date
			</label>
			<input class="ttw-date date" id="people_end" maxlength="524288" name="people_end"
			size="20" tabindex="0" title=""
				{if isset($people)}
				value="{$people->people_end}"
				{/if}
			>
		</div>

		<div id="people_bio-container" class="field f_100">
			<label for="people_bio">
				Bio
			</label>
			<textarea name="people_bio" id="people_bio">{if isset($people)}{$people->people_bio}{/if}</textarea>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Submit">
		</div>

		{if isset($people)}
		<hr/>

		<div id="image-container" class="field f_100">
			<label for="image">
				Images<span class="smalltext">(.jpg, .gif, or .png)</span>
			</label>
			<input type="file" name="image" accept="image/*"/>
			<div class="image-list">
			{if isset($refrences)}
				{for $i=0; $i < $refrences.image.count; $i++}
					{assign var=img value=$refrences.image.rows[$i]}
					<img id="image-{$i}" src="{$weburl}files/people/image/thumb/{$img->image_filename}"/>
				{/for}
			{/if}
			</div>
		</div>

		<div id="research-container" class="field f_100">
			<label for="research">
				Research
			</label>
			<input class="refrence" autocomplete=off type="text" name="research" />
			<div class="tiny-list">
			</div>
		</div>

		<div id="publication-container" class="field f_100">
			<label for="publication">
				Publication
			</label>
			<input class="refrence" autocomplete=off type="text" name="publication" />
			<div class="tiny-list">
			</div>
		</div>
		{/if}
	</form>
</div>

<br clear="all" />

{*---------------------------------------------------------------------------*}

{if isset($people_list)}
	{include "templates/snippets/section_title.tpl" title={t s='People' m=0}}
	<ul class="admin">
	{for $i=0; $i < $people_list.count; $i++}
		<li>
		{assign var=ppl value=$people_list.rows[$i]}
		<span class="title">{$ppl->people_firstname} {$ppl->people_middlename} {$ppl->people_lastname}</span>
		<span class="edit"><a href='{gl url="admin/people/view/{$ppl->people_id}"}'>edit</a></span>
		<span class="remove"><a href='{gl url="admin/people/remove/{$ppl->people_id}"}'>remove</a></span>
		</li>
	{/for}
	</ul>
{/if}

{*---------------------------------------------------------------------------*}
