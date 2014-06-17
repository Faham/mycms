
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

{include "templates/snippets/section_title.tpl" title={t s='Add research' m=0}}

<div class="TTWForm-container">

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/research/create"}'
	>

		<div id="research_title-container" class="field f_100">
			<label for="research_title">
				Title
			</label>
			<input type="text" name="research_title" id="research_title" required>
		</div>

		<div id="research_status-container" class="field f_100">
			<label for="research_status">
				Status
			</label>

			<select name="research_status" id="research_status" required>
				<option id="research_status-1" value="active">
					{t s='active' m=0}
				</option>
				<option id="research_status-2" value="future">
					{t s='future' m=0}
				</option>
				<option id="research_status-3" value="onhold">
					{t s='onhold' m=0}
				</option>
				<option id="research_status-4" value="past">
					{t s='past' m=0}
				</option>
				<option id="research_status-5" value="unknown">
					{t s='unknown' m=0}
				</option>
			</select>
		</div>

		<div id="research_priority-container" class="field f_100">
			<label for="research_priority">
				Priority
			</label>
			<input type="number" name="research_priority" id="research_priority" min=0>
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
			<textarea name="research_description" id="research_description"></textarea>
		</div>

		<div id="image-container" class="field f_100">
			<label for="image" class="label">
				Upload image<div class="smalltext">(.jpg, .gif, or .png)</div>
			</label>
			<div class="original_div">
				<input type="file" name="image[]" accept="image/*"/>
				<a class="addImage"><font size="3">Add More Images</font></a>
			</div>
			<div class="addtionalImages">
				<ol class="image_list">
					
				</ol>
			</div>
			
			<div class="image-thumb-list">
			</div>
		</div>

		<div id="video-container" class="field f_100">
			<label for="video" class="label">
				Upload video<div class="smalltext">(.mp4, .webm, .ogg)</div>
			</label>
			<div class="original_div">
				<input type="file" name="video[]" accept="video/*"/>
				<a class="addVideo"><font size="3">Add More Videos</font></a>
			</div>
			<!--<button id="add_video">more video</button>-->
			<div class="addtionalVideos">
				<ol class="video_list">
					
				</ol>
			</div>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Save">
		</div>

	</form>
</div>

<br clear="all" />

{*---------------------------------------------------------------------------*}
