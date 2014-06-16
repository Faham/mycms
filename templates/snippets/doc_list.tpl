
{*---------------------------------------------------------------------------*}

<div class="doc-list">
<ul>
{if isset($doc) and isset($content)}
	{for $i=0; $i < $doc.count; $i++}
		{assign var=dc value=$doc.rows[$i]}
		<a  id="doc-{$i}" href='{gl url="files/{$content}/doc/{$dc.doc_filename}"}'/>
			<li>
				<img class="docimage" src='{gl url="static/images/pdf.png"}'/>
				<p><a class="remove" href='{gl url="admin/{$content}/removeDoc"}/{$dc.doc_id}--{$contentId}'>remove document</a></p>
			</li>
		</a>
	{/for}
{/if}
</ul>
</div>

{*---------------------------------------------------------------------------*}
