
{*---------------------------------------------------------------------------*}

<div class="doc-list">
{if isset($doc) and isset($content)}
	{for $i=0; $i < $doc.count; $i++}
		{assign var=dc value=$doc.rows[$i]}
		<a  id="doc-{$i}" href='{gl url="files/{$content}/doc/{$dc.doc_filename}"}'/>
			<img class="docimage" src='{gl url="static/images/pdf.png"}'/>
		</a>
	{/for}
{/if}
</div>

{*---------------------------------------------------------------------------*}
