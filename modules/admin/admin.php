<?php

//-----------------------------------------------------------------------------

require_once('admin.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['action'])) $_GET['action'] = 'home';
$g['template'] = 'home';

//-----------------------------------------------------------------------------

// Set main menu options
$menu = [
	['name' => 'People',       'url' => 'admin/people',       ],
	['name' => 'Research',     'url' => 'admin/research',     ],
	['name' => 'Publications', 'url' => 'admin/publications', ]
];
$g['smarty']->assign('menu', $menu);

//-----------------------------------------------------------------------------

switch($_GET['action']){

//-----------------------------------------------------------------------------

default:
case 'people': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->get('default', "people.people_id = $id");

		if (!$ppl['error'] && $ppl['count'] > 0)
			$p = $ppl['rows'][0];
			$g['smarty']->assign('page_l', implode(' ', array($p['people_firstname'], $p['people_middlename'], $p['people_lastname'])));
			$g['smarty']->assign('people', $p);
			
		$g['template'] = 'snippets/people_default';
	} else {
		$g['smarty']->assign('page', 'People');
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->get('teaser', '', 'people.people_group, people.people_firstname ASC');

		if (!$ppl['error'] && $ppl['count'] > 0)
			$g['smarty']->assign('people', $ppl);
			
		$g['template'] = 'admin';
	}
} break;

//-----------------------------------------------------------------------------

case 'research': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'Research');
		$r = $g['content']['research']->get('default', "research.research_id = $id");

		if (!$r['error'] && $r['count'] > 0)
			$p = $r['rows'][0];
			$g['smarty']->assign('page_l', $p['research_title']);
			$g['smarty']->assign('research', $p);
			
		$g['template'] = 'snippets/research_default';
	} else {
		$g['smarty']->assign('page', 'Research');
		$g['smarty']->assign('selectedmenu', 'Research');
		$research = $g['content']['research']->get('teaser', '', 'research.research_priority DESC, research.research_status');

		if (!$research['error'] && $research['count'] > 0)
			$g['smarty']->assign('research', $research);
			
		$g['template'] = 'admin';
	}
} break;

//-----------------------------------------------------------------------------

case 'publications': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'Publications');
		$r = $g['content']['publication']->get('default', "publication.publication_id = $id");

		if (!$r['error'] && $r['count'] > 0)
			$p = $r['rows'][0];
			$g['smarty']->assign('page_l', $p['publication_title']);
			$g['smarty']->assign('publication', $p);
			
		$g['template'] = 'snippets/publication_default';
	} else {
		$g['smarty']->assign('page', 'Publications');
		$g['smarty']->assign('selectedmenu', 'Publications');
		$pubs = $g['content']['publication']->get('teaser', '', 'publication.publication_year DESC');

		if (!$pubs['error'] && $pubs['count'] > 0)
			$g['smarty']->assign('publications', $pubs);
			
		$g['template'] = 'admin';
	}
} break;

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
