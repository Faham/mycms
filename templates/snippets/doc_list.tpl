
{*---------------------------------------------------------------------------*}

<div class="doc-list">
{if isset($doc) and isset($content)}
	{for $i=0; $i < $doc.count; $i++}
		{assign var=img value=$doc.rows[$i]}
		<img id="doc-{$i}" src="{$weburl}files/{$content}/doc/{$doc.doc_filename}"/>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
