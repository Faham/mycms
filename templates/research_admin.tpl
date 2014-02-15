
{*---------------------------------------------------------------------------*}

{if isset($research)}
{include "templates/snippets/section_title.tpl" title={t s='Edit research' m=0}}
{else}
{include "templates/snippets/section_title.tpl" title={t s='Add research' m=0}}
{/if}

<div class="TTWForm-container"
			{if isset($research)}
			data-type='research'
			data-id='{$research->research_id}'
			{/if}
>

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
			{if isset($research)}
			action='{gl url="admin/research/edit"}/{$research->research_id}'
			{else}
			action='{gl url="admin/research/create"}'
			{/if}
	>

		<div id="research_title-container" class="field f_100">
			<label for="research_title">
				Title
			</label>
			<input type="text" name="research_title" id="research_title" required
				{if isset($research)}
				value="{$research->research_title}"
				{/if}
			>
		</div>


		<div id="research_status-container" class="field f_100">
			<label for="field11">
				Status
			</label>

			<select name="research_status" id="research_status" required>
				<option id="research_status-1" value="active"
				{if isset($people) and "active" == $people->research_status}
					selected
				{/if}
				>
					{t s='active' m=0}
				</option>
				<option id="research_status-2" value="future"
				{if isset($people) and "future" == $people->research_status}
					selected
				{/if}
				>
					{t s='future' m=0}
				</option>
				<option id="research_status-3" value="onhold"
				{if isset($people) and "onhold" == $people->research_status}
					selected
				{/if}
				>
					{t s='onhold' m=0}
				</option>
				<option id="research_status-4" value="past"
				{if isset($people) and "past" == $people->research_status}
					selected
				{/if}
				>
					{t s='past' m=0}
				</option>
				<option id="research_status-5" value="unknown"
				{if isset($people) and "unknown" == $people->research_status}
					selected
				{/if}
				>
					{t s='unknown' m=0}
				</option>
			</select>
		</div>

		<div id="research_priority-container" class="field f_100">
			<label for="field11">
				Priority
			</label>

			<select name="research_priority" id="research_priority" required>
				<option id="research_priority-1" value="1"
				{if isset($people) and "1" == $people->research_priority}
					selected
				{/if}
				>1</option>
				<option id="research_priority-2" value="2"
				{if isset($people) and "2" == $people->research_priority}
					selected
				{/if}
				>2</option>
				<option id="research_priority-3" value="3"
				{if isset($people) and "3" == $people->research_priority}
					selected
				{/if}
				>3</option>
				<option id="research_priority-4" value="4"
				{if isset($people) and "4" == $people->research_priority}
					selected
				{/if}
				>4</option>
				<option id="research_priority-5" value="5"
				{if isset($people) and "5" == $people->research_priority}
					selected
				{/if}
				>5</option>
			</select>
		</div>

		<div id="research_summary-container" class="field f_100">
			<label for="research_summary">
				Summary
			</label>
			<input type="text" name="research_summary" id="research_summary"
				{if isset($research)}
				value="{$research->research_summary}"
				{/if}
			>
		</div>

		<div id="research_description-container" class="field f_100">
			<label for="research_description">
				Description
			</label>
			<textarea name="research_description" id="research_description">{if isset($research)}{$research->research_description}{/if}</textarea>
		</div>


		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Submit">
		</div>

		{if isset($research)}

		{include "templates/snippets/section_title.tpl" title={t s='Images' m=0}}
		<div id="image-container" class="field f_100">
			<label for="image">
				Upload image<div class="smalltext">(.jpg, .gif, or .png)</div>
			</label>
			<input type="file" name="image" accept="image/*"/>
			{if isset($refrences)}
				{include "templates/snippets/image_thumb_list.tpl" research=$refrences.image content='research'}
			{/if}
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='Videos' m=0}}
		<div id="video-container" class="field f_100">
			<label for="video">
				Upload video<div class="smalltext">(.mp4, .webm, .ogg)</div>
			</label>
			<input type="file" name="video" accept="video/*"/>
			{if isset($refrences)}
				{include "templates/snippets/video_list.tpl" research=$refrences.video content='research'}
			{/if}
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='People' m=0}}
		<div id="people-container" class="field f_100">
			<label for="people">
				Add people
			</label>
			<input class="refrence" autocomplete=off type="text" name="people" placeholder="search"/>
			{if isset($refrences)}
				{include "templates/snippets/people_refrence_list.tpl" people=$refrences.people}
			{/if}
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='Publication' m=0}}
		<div id="publication-container" class="field f_100">
			<label for="publication">
				Add publication
			</label>
			<input class="refrence" autocomplete=off type="text" name="publication" placeholder="search"/>
			{if isset($refrences)}
				{include "templates/snippets/publication_teaser_list.tpl" publication=$refrences.publication}
			{/if}
		</div>

		{/if}
	</form>
</div>

<br clear="all" />

{*---------------------------------------------------------------------------*}

{if isset($research_list)}
	{include "templates/snippets/section_title.tpl" title={t s='Research' m=0}}
	<table cellpadding='0' cellspacing='0' class='persontable'10>
	{for $i=0; $i < $research_list.count; $i++}
		{assign var=rch value=$research_list.rows[$i]}
		<tr>
		<td>{$rch->research_title}</td>
		<td><a href='{gl url="admin/research/view/{$rch->research_id}"}' class="edit">edit</a></td>
		<td><a href='{gl url="admin/research/remove/{$rch->research_id}"}' class="remove">remove</a></td>
		</tr>
	{/for}
	</table>
{/if}


{*---------------------------------------------------------------------------*}
