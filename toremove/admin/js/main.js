function $(a)
{
	return document.getElementById(a);
}

function hide(a)
{
	a.style.display = 'none';
}

function show(a)
{
	a.style.display = '';
}

function focusFirstTextbox()
{
	var firstText = document.getElementById("firstText");
	
	if (firstText)
	{
		firstText.focus();
	}
}

function changeType()
{
	show($('schoolrow'));
	show($('numberrow'));
	show($('volumerow'));
	show($('issuerow'));
	show($('seriesrow'));
	show($('locationrow'));
	show($('pagesrow'));
	show($('isbnrow'));
	show($('editionrow'));
	show($('chapterrow'));
	
	var type = 1;
	var radio = document.publication.TYPE;
	var i;
	
	for (i=1; i<=radio.length; i++)
	{
		if (radio[i-1].checked)
		{
			type = i;
		}
	}
	
	if (type == 1 || type == 2 || type == 5 || type == 6 || type == 7)
	{
		hide($('isbnrow'));
		hide($('editionrow'));
		hide($('chapterrow'));
	}
	
	if (type == 3 || type == 4)
	{
		hide($('locationrow'));
	}
	
	if (type == 5 || type == 6 || type == 7)
	{
		hide($('volumerow'));
		hide($('issuerow'));
		hide($('seriesrow'));
	}
	
	if (type == 6 || type == 7)
	{
		hide($('pagesrow'));
	}
	
	if (type != 5)
	{
		hide($('numberrow'));
	}
	
	if (type < 5)
	{
		hide($('schoolrow'));
	}
}

function editAuthors()
{
	var width = 330;
	var height = 525;
	var left = (screen.width/2)-(width/2);
	var top = (screen.height/2)-(height/2)-50;
	window.open('editauthors.php?idList='+$('idList').value,'authors','width='+width+',height='+height+',top='+top+',left='+left+',scrollbars=1');
}

function saveOrder(item) {
	var group = item.toolManDragGroup;
	var list = group.element.parentNode;
	var id = list.getAttribute("id");
	if (id == null) return;
	group.register('dragend', function() {
		$('idList1').value = junkdrawer.serializeList(list);
		$('idList2').value = junkdrawer.serializeList(list);
		var idList = junkdrawer.serializeList(list).split('|');
		builtList = [];
		for (var i=0; i<idList.length; i++)
		{
			builtList[i] = nameList[idList[i]];
		}
		$('nameList').value = builtList.join(', ');
	})
}

function removeAuthor(id)
{
	var auth = $(id);
	if (auth)
	{
		auth.parentNode.removeChild(auth);
	}
	var list = $('sortable');
	$('idList1').value = junkdrawer.serializeList(list);
	$('idList2').value = junkdrawer.serializeList(list);
	var idList = junkdrawer.serializeList(list).split('|');
	builtList = [];
	for (var i=0; i<idList.length; i++)
	{
		builtList[i] = nameList[idList[i]];
	}
	$('nameList').value = builtList.join(', ');
}

function saveAndClose()
{
	window.opener.$('idList').value = $('idList1').value;
	window.opener.$('authorList').innerHTML = $('nameList').value;
	close();
}

function addPubRow()
{
	var pubcopy = $('pubcopy');
	var sortable2 = $('sortable2');
	
	var pubchild = document.createElement('li');
	pubchild.innerHTML = pubcopy.innerHTML;
	pubchild.children[0].selectedIndex = 0;
	
	sortable2.appendChild(pubchild);
	window.dragsort.makeListSortable($("sortable2"));
}

function publicationModeChange()
{
	if ($('mode0') && $('mode0').checked)
	{
		if ($('customPub0'))
			$('customPub0').disabled = true;
		
		if ($('customPub1'))
			$('customPub1').disabled = true;
		
		if ($('customPub2'))
			$('customPub2').disabled = true;
		
		if ($('customPub3'))
			$('customPub3').disabled = true;
		
		if ($('customPub4'))
			$('customPub4').disabled = true;
	}
	else
	{
		if ($('customPub0'))
			$('customPub0').disabled = false;
		
		if ($('customPub1'))
			$('customPub1').disabled = false;
		
		if ($('customPub2'))
			$('customPub2').disabled = false;
		
		if ($('customPub3'))
			$('customPub3').disabled = false;
		
		if ($('customPub4'))
			$('customPub4').disabled = false;
	}
}

function projectModeChange()
{
	if ($('promode0') && $('promode0').checked)
	{
		if ($('customPro0'))
			$('customPro0').disabled = true;
		
		if ($('customPro1'))
			$('customPro1').disabled = true;
		
		if ($('customPro2'))
			$('customPro2').disabled = true;
		
		if ($('customPro3'))
			$('customPro3').disabled = true;
		
		if ($('customPro4'))
			$('customPro4').disabled = true;
	}
	else
	{
		if ($('customPro0'))
			$('customPro0').disabled = false;
		
		if ($('customPro1'))
			$('customPro1').disabled = false;
		
		if ($('customPro2'))
			$('customPro2').disabled = false;
		
		if ($('customPro3'))
			$('customPro3').disabled = false;
		
		if ($('customPro4'))
			$('customPro4').disabled = false;
	}
}

function doLoad()
{
	focusFirstTextbox();
	
	if ($("sortable2"))
	{
		window.dragsort = ToolMan.dragsort();
		window.junkdrawer = ToolMan.junkdrawer();
		dragsort.makeListSortable($("sortable2"));
	}
	
	if ($("sortable3"))
	{
		window.dragsort = ToolMan.dragsort();
		window.junkdrawer = ToolMan.junkdrawer();
		dragsort.makeListSortable($("sortable3"));
	}
}