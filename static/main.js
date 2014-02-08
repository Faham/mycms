function $(a)
{
	return document.getElementById(a);
}

function checkKey(ev)
{
	if (ev.keyCode == 13)
	{
		doSearch();
	}
}

function doSearch()
{
	var q = $('searchText').value;
	window.location = "http://www.google.ca/search?q=" + q + "+site:hci.usask.ca";
}

function doLoad()
{
	if (window.GUnload)
	{
		window.onunload = GUnload;
	}
}