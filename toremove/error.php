<?php

require_once "common.php";

dbConnect();

outputHeader(0, "Page Not Found");

?>

<h2>Page Not Found</h2>

<p>The page that you were looking for could not be found!</p>


<p>
	<div style="margin-bottom: 4px; font-weight: bold">Search hci.usask.ca</div>
	<input type="text" id="searchText" onkeypress="checkKey(event)" /> <input type="button" value="Search" onclick="doSearch()" />
</p>

<?php

outputFooter();

?>