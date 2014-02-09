<?php

//-----------------------------------------------------------------------------

require_once 'DB.php';
require_once('admin.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['action'])) $_GET['action'] = 'people';
$g['template'] = 'people_admin';

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

	/*
	if (isset($_GET['id'])) {
		$p = $people->get($_GET['id']);
	} else {
		$p = $people->get(1);
	}
	*/

	$people = $g['content']['people']->getall();
	$g['smarty']->assign('selectedmenu', 'People');
	$g['smarty']->assign('people', $people);
	$g['template'] = 'people_admin';

} break;

//-----------------------------------------------------------------------------

case 'research': {

	$research = $g['content']['research']->getall();
	$g['smarty']->assign('selectedmenu', 'Research');
	$g['smarty']->assign('research', $research);
	$g['template'] = 'research_admin';

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
