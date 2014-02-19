
{*---------------------------------------------------------------------------*}

{strip}
<div class="bibtex">
	{assign var=p value=$publication}
	{if isset($p.people) and $p.people.count > 0}
		{for $i = 0; $i < $p.people.count; $i++}
			{if $i > 0},&nbsp;{/if}
			{$p.people.rows[$i].people_lastname}, {$p.people.rows[$i].people_firstname|substr:0:1|upper}.
		{/for}
	{/if}
	{if !empty($p.publication_year)}&nbsp;({$p.publication_year}),{/if}
	{if !empty($p.publication_title)}&nbsp;{$p.publication_title}{/if}
	{if !empty($p.publication_type) and $p.publication_type == 'mastersthesis'}&nbsp;<i>M.Sc. Thesis</i>{/if}
	{if !empty($p.publication_type) and $p.publication_type == 'phdthesis'}&nbsp;<i>Ph.D. Dissertation</i>{/if}
	{if !empty($p.publication_journal)},&nbsp;<i>{$p.publication_journal}</i>{/if}
	{if !empty($p.publication_volume)     or
		!empty($p.publication_number)     or
		!empty($p.publication_series)     or
		!empty($p.publication_isbn)       or
		!empty($p.publication_edition)    or
		!empty($p.publication_pages)      or
		!empty($p.publication_address)    or
		!empty($p.publication_technumber) or
		!empty($p.publication_school)     or
		!empty($p.publication_toappear)   or
		!empty($p.publication_note)},
	{else}.{/if}
	{if !empty($p.publication_volume)}&nbsp;vol.&nbsp;{$p.publication_volume}{/if}
	{if !empty($p.publication_number)}&nbsp;no.&nbsp;{$p.publication_number}{/if}
	{if !empty($p.publication_volume) or $p.publication_number},{/if}
	{if !empty($p.publication_series)}&nbsp;{$p.publication_series}.{/if}
	{if !empty($p.publication_isbn)}&nbsp;ISBN&nbsp;{$p.publication_isbn}.{/if}
	{if !empty($p.publication_edition)}&nbsp;{$p.publication_edition}&nbsp;edition.{/if}
	{if !empty($p.publication_chapter)}&nbsp;Chapter&nbsp;{$p.publication_chapter}.{/if}
	{if !empty($p.publication_address)}&nbsp;{$p.publication_address}.{/if}
	{if !empty($p.publication_pages)}&nbsp;{$p.publication_pages}.{/if}
	{if !empty($p.publication_technumber)}&nbsp;<i>Technical Report&nbsp;{$p.publication_technumber}&nbsp;</i>,{/if}
	{if !empty($p.publication_school)}&nbsp;<i>{$p.publication_school}</i>.{/if}
	{if !empty($p.publication_toappear) and not $p.publication_toappear}&nbsp;To appear.{/if}
	{if !empty($p.publication_note)}&nbsp;{$p.publication_note}.{/if}
	{if !empty($p.publication_doi_number)}&nbsp;&lt;doi:{$p.publication_doi_number}&gt;{/if}
</div>
{/strip}

{*---------------------------------------------------------------------------*}
