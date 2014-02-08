
{*---------------------------------------------------------------------------*}

<div class="bibtex">
	{assign var=p value=$publication}
	{if isset($p.people) and $p.people.count > 0}
		{for $i = 0; $i < $p.people.count; $i++}
			{if $i > 0}, &nbsp;{/if}
			{$p.people.rows[$i].people_lastname}, {$p.people.rows[$i].people_firstname|substr:0:1|upper}.
		{/for}
	{/if}
	{if $p.publication_year}                          &nbsp;({$p.publication_year}),{/if}
	{if $p.publication_booktitle}                     &nbsp;<i>{$p.publication_booktitle}</i>{/if}
	{if $p.publication_type == 'mastersthesis'}       &nbsp;<i>M.Sc. Thesis</i>{/if}
	{if $p.publication_type == 'phdthesis'} &nbsp;<i>Ph.D. Dissertation</i>{/if}
	{if $p.publication_journal}                     ,&nbsp;<i>$p.publication_journal</i>{/if}
	{if $p.publication_volumenum or
		$p.publication_issuenum or
		$p.publication_series or
		$p.publication_isbn or
		$p.publication_edition or
		$p.publication_pages or
		$p.publication_address or
		$p.publication_technumber or
		$p.publication_school or
		$p.publication_toappear or
		$p.publication_note},
	{else}.{/if}
	{if $p.publication_volumenum}     &nbsp;vol.&nbsp;               {$p.publication_volumenum}{/if}
	{if $p.publication_issuenum}      &nbsp;no.&nbsp;                {$p.publication_issuenum}{/if}
	{if $p.publication_volumenum or $p.publication_issuenum},{/if}
	{if $p.publication_series}        &nbsp;                         {$p.publication_series}.{/if}
	{if $p.publication_isbn}          &nbsp;ISBN&nbsp;               {$p.publication_isbn}.{/if}
	{if $p.publication_edition}       &nbsp;                         {$p.publication_edition}&nbsp;edition.{/if}
	{if $p.publication_chapter}       &nbsp;Chapter&nbsp;            {$p.publication_chapter}.{/if}
	{if $p.publication_address}       &nbsp;                         {$p.publication_address}.{/if}
	{if $p.publication_pages}         &nbsp;                         {$p.publication_pages}.{/if}
	{if $p.publication_technumber}    &nbsp;<i>Technical Report&nbsp;{$p.publication_technumber}&nbsp;</i>,{/if}
	{if $p.publication_school}        &nbsp;<i>                      {$p.publication_school}</i>.{/if}
	{if not $p.publication_toappear}  &nbsp;To appear.{/if}
	{if $p.publication_note}          &nbsp;                         {$p.publication_note}.{/if}
	{if $p.publication_doi_number}    &nbsp;&lt;<a href="http://dx.doi.org/{$p.publication_doi_number}">doi:{$p.publication_doi_number}</a>&gt;{/if}
</div>
{*---------------------------------------------------------------------------*}
