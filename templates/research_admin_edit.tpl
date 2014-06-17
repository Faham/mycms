
{*---------------------------------------------------------------------------*}

<img id='remove-refrence-button' src='{gl url="static/images/recyclebin.png"}'/>

{*---------------------------------------------------------------------------*}

<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/research/create"}'>
	<div id="research-container" class="field f_100">
		<label for="research">
			Find research
		</label>
		<input class="find" autocomplete=off type="text" name="research" placeholder="search"/>
	</div>
</form>

{*---------------------------------------------------------------------------*}

{if isset($research)}
{include "templates/snippets/section_title.tpl" title={t s='Edit research' m=0}}

<div class="TTWForm-container"
			data-type='research'
			data-id='{$research.research_id}'
>

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
			action='{gl url="admin/research/edit"}/{$research.research_id}'
	>

		<a class="remove" href='{gl url="admin/research/remove"}/{$research.research_id}'>remove</a>

		<div id="research_title-container" class="field f_100">
			<label for="research_title">
				Title
			</label>
			<input type="text" name="research_title" id="research_title" required
				value="{$research.research_title}">
		</div>

		<div id="research_status-container" class="field f_100">
			<label for="research_status">
				Status
			</label>

			<select name="research_status" id="research_status" required>
				<option id="research_status-1" value="active"
				{if "active" == $research.research_status}
					selected
				{/if}
				>
					{t s='active' m=0}
				</option>
				<option id="research_status-2" value="future"
				{if "future" == $research.research_status}
					selected
				{/if}
				>
					{t s='future' m=0}
				</option>
				<option id="research_status-3" value="onhold"
				{if "onhold" == $research.research_status}
					selected
				{/if}
				>
					{t s='onhold' m=0}
				</option>
				<option id="research_status-4" value="past"
				{if "past" == $research.research_status}
					selected
				{/if}
				>
					{t s='past' m=0}
				</option>
				<option id="research_status-5" value="unknown"
				{if "unknown" == $research.research_status}
					selected
				{/if}
				>
					{t s='unknown' m=0}
				</option>
			</select>
		</div>

		<div id="research_priority-container" class="field f_100">
			<label for="research_priority">
				Priority
			</label>
			<input type="number" name="research_priority" id="research_priority"
				value="{$research.research_priority}" min=0>
		</div>

		<div id="research_summary-container" class="field f_100">
			<label for="research_summary">
				Summary
			</label>
			<input type="text" name="research_summary" id="research_summary"
				value="{$research.research_summary}">
		</div>

		<div id="research_description-container" class="field f_100">
			<label for="research_description">
				Description
			</label>
			<textarea name="research_description" id="research_description">{$research.research_description}</textarea>
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
				<!--<span class="info"></span>-->
				<ol class="image_list">
					
				</ol>
			</div>
			{include "templates/snippets/image_thumb_list.tpl" image=$research.image content='research' contentId=$research.research_id}
			<!--remove image link-->
			<a class="remove" href='{gl url="admin/research/removeAllImages"}/{$research.research_id}'>remove all images</a>
		</div>

		<div id="video-container" class="field f_100">
			<label for="video" class="label">
				Upload video<div class="smalltext">(.mp4, .webm, .ogg)</div>
			</label>
			<div class="original_div">
				<input type="file" name="video[]" accept="video/*"/>
				<a class="addVideo"><font size="2">Add More Videos</font></a>
			</div>
			<div class="addtionalVideos">
				<ol class="video_list">
					
				</ol>
			</div>
			{include "templates/snippets/video_list.tpl" video=$research.video content='research' contentId=$research.research_id}
			<!--remove video link-->
			<a class="remove" href='{gl url="admin/research/removeAllVideos"}/{$research.research_id}'>remove all videos</a>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Save">
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='People' m=0}}
		<div id="people-container" class="field f_100">
			<label for="people">
				Add people
			</label>
			<input class="refrence" autocomplete=off type="text" name="people" placeholder="search"/>
			<div class="removable-refrence-list">
				{include "templates/snippets/people_refrence_list.tpl" people=$research.people}
			</div>
		</div>

		{include "templates/snippets/section_title.tpl" title={t s='Publication' m=0}}
		<div id="publication-container" class="field f_100">
			<label for="publication">
				Add publication
			</label>
			<input class="refrence" autocomplete=off type="text" name="publication" placeholder="search"/>
			<div class="removable-refrence-list">
				{include "templates/snippets/publication_teaser_list.tpl" publication=$research.publication}
			</div>
		</div>

	</form>
</div>

{/if}

<br clear="all" />

{*---------------------------------------------------------------------------*}
