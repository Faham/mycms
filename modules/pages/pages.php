<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once('pages.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['action']) || empty($_GET['action'])) $_GET['action'] = 'home';
$g['template'] = 'home';

//-----------------------------------------------------------------------------

// Set main menu options
$menu = array(
	array('name' => 'Home',         'url' => '',             ),
	array('name' => 'People',       'url' => 'people',       ),
	array('name' => 'Research',     'url' => 'research',     ),
	array('name' => 'Publications', 'url' => 'publications', ),
	array('name' => 'Courses',      'url' => 'courses',      ),
	array('name' => 'Download',     'url' => 'download',     ),
	array('name' => 'Contact',      'url' => 'contact',      ),
);
$g['smarty']->assign('menu', $menu);

//-----------------------------------------------------------------------------

switch($_GET['action']){

//-----------------------------------------------------------------------------

case 'home': {
	$imglist = pages::get_imagelist(true);
	if (!$imglist['error'] && $imglist['count'] > 0)
		$g['smarty']->assign('imglist', $imglist);

	$faculty = $g['content']['people']->view('teaser', 'people.people_group = "faculty"');
	if (!$faculty['error'] && $faculty['count'] > 0)
		$g['smarty']->assign('faculty', $faculty);

	$research = $g['content']['research']->view('teaser', 'research.research_status = "active"', 'research.research_priority DESC', '0,3');
	if (!$research['error'] && $research['count'] > 0)
		$g['smarty']->assign('research', $research);

	$publication = $g['content']['publication']->view('teaser', '', 'publication.publication_year DESC', '0,3');
	if (!$publication['error'] && $publication['count'] > 0)
		$g['smarty']->assign('publication', $publication);

	$g['smarty']->assign('selectedmenu', 'Home');
	$g['template'] = 'home';
} break;

//-----------------------------------------------------------------------------

case 'people': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->view('default', "people.people_id = $id");

		if (!$ppl['error'] && $ppl['count'] > 0)
			$p = $ppl['rows'][0];
			$g['smarty']->assign('page_l', implode(' ', array($p['people_firstname'], $p['people_middlename'], $p['people_lastname'])));
			$g['smarty']->assign('people', $p);

		$g['template'] = 'snippets/people_default';
	} else {
		$g['smarty']->assign('page', 'People');
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->view('teaser', '', 'people.people_group, people.people_firstname ASC', '');

		if (!$ppl['error'] && $ppl['count'] > 0)
			$g['smarty']->assign('people', $ppl);

		$g['template'] = 'people';
	}
} break;

//-----------------------------------------------------------------------------

case 'research': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'Research');
		$r = $g['content']['research']->view('default', "research.research_id = $id");

		if (!$r['error'] && $r['count'] > 0)
			$p = $r['rows'][0];
			$g['smarty']->assign('page_l', $p['research_title']);
			$g['smarty']->assign('research', $p);

		$g['template'] = 'snippets/research_default';
	} else {
		$g['smarty']->assign('page', 'Research');
		$g['smarty']->assign('selectedmenu', 'Research');
		$research = $g['content']['research']->view('teaser', '', 'research.research_priority DESC, research.research_status');

		if (!$research['error'] && $research['count'] > 0)
			$g['smarty']->assign('research', $research);

		$g['template'] = 'research';
	}
} break;

//-----------------------------------------------------------------------------

case 'publications': {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'Publications');
		$r = $g['content']['publication']->view('default', "publication.publication_id = $id");

		if (!$r['error'] && $r['count'] > 0)
			$p = $r['rows'][0];
			$g['smarty']->assign('page_l', $p['publication_title']);
			$g['smarty']->assign('publication', $p);

		$g['template'] = 'snippets/publication_default';
	} else {
		$g['smarty']->assign('page', 'Publications');
		$g['smarty']->assign('selectedmenu', 'Publications');
		$pubs = $g['content']['publication']->view('teaser', '', 'publication.publication_year DESC');

		if (!$pubs['error'] && $pubs['count'] > 0)
			$g['smarty']->assign('publications', $pubs);

		$g['template'] = 'publication';
	}
} break;

//-----------------------------------------------------------------------------

case 'courses': {
	$g['smarty']->assign('page', 'Courses');
	$g['smarty']->assign('selectedmenu', 'Courses');
	$g['template'] = 'courses';
} break;

//-----------------------------------------------------------------------------

case 'download': {
	$g['smarty']->assign('page', 'Download');
	$g['smarty']->assign('selectedmenu', 'Download');
	$g['template'] = 'download';
} break;

//-----------------------------------------------------------------------------

case 'contact': {
	$g['smarty']->assign('page', 'Contact');
	$g['smarty']->assign('selectedmenu', 'Contact');
	$g['template'] = 'contact';
} break;

//-----------------------------------------------------------------------------

default: {
	$g['smarty']->assign('page', 'Error');
	$g['template'] = 'notfound';
}

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
