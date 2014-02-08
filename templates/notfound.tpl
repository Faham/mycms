
<h2>{t s=PAGE_NOT_FOUND m=0}</h2>
<p>{t s=NO_PAGE m=0}</p>
<div style="margin-bottom: 4px; font-weight: bold">{t s=SEARCH m=0} {$weburl}</div>
<input type="text" id="searchText" onkeypress="checkKey(event)">
<input type="button" value="Search" onclick="doSearch()">
