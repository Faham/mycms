
{*---------------------------------------------------------------------------*}

{strip}
<div class="bibtex">
	{assign var=p value=$publication}
	<div>
		<div>
			&#64;{$p.publication_type|ucfirst}&nbsp;{ldelim}
		</div>
	</div>
	{if isset($p.people) and $p.people.count > 0}
		<div>
			<div class="bibtexleft">author</div><div class="bibtexmiddle">=</div>
				<div class="bibtexright">
					{ldelim}
					{for $i = 0; $i < $p.people.count; $i++}
						{if $i > 0}&nbsp;and&nbsp;{/if}
						{$p.people.rows[$i].people_firstname}&nbsp;
						{if not empty($p.people.rows[$i].people_middlename)}
							{$p.people.rows[$i].people_middlename}&nbsp;
						{/if}
						{$p.people.rows[$i].people_lastname}
					{/for}
					{rdelim},
				</div>
		</div>
	{/if}

	{$fields = [
		'title',
		'journal',
		'year',
		'volume',
		'number',
		'series',
		'address',
		'technumber',
		'school',
		'pages',
		'edition',
		'chapter',
		'note',
		'booktitle',
		'howpublished',
		'institution',
		'month',
		'organization',
		'publisher',
		'type',
		'url'
	]}

	{assign var=count value=$fields|@count}
	{for $i = 0; $i < $count; $i++}
		{assign var=f value={'publication_'|cat:{$fields[$i]}}}
		{if isset($p.$f) and !empty($p.$f)}
			<div class="bibtexentry">
				<div class="bibtexleft">{$fields[$i]}</div>
				<div class="bibtexmiddle">=</div>
				<div class="bibtexright">
					{ldelim}{$p.$f}{rdelim},
				</div>
			</div>
			<div class="cb"></div>
		{/if}
	{/for}
	{rdelim}
</div>
{/strip}

{*---------------------------------------------------------------------------*}
