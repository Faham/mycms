
{*---------------------------------------------------------------------------*}

<div class="research-teaser-list">
	<ul class="research_list"><!--use ul-li to implement show/hide-->
		{for $i=0; $i < $research.count; $i++}
			<li class="research_li">{include "templates/snippets/research_teaser.tpl" research=$research.rows[$i]}<li>
		{/for}
	</ul>
</div>

{*---------------------------------------------------------------------------*}
