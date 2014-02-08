<?php

//=============================================================================

require_once('classes/content.php');
global $g;

//=============================================================================

class image extends content {
	
	public function __construct() {
		parent::__construct('image');
	}
}

//=============================================================================

class video extends content {
	
	public function __construct() {
		parent::__construct('video');
	}
}

//=============================================================================

class doc extends content {
	
	public function __construct() {
		parent::__construct('doc');
	}
}

//=============================================================================

class people extends content {
	
	public function __construct() {
		parent::__construct('people');
	}
}

//=============================================================================

class research extends content {
	
	public function __construct() {
		parent::__construct('research');
	}
}

//=============================================================================

class publication extends content {
	
	public function __construct() {
		parent::__construct('publication');
	}
}

//=============================================================================

class course extends content {
	
	public function __construct() {
		parent::__construct('course');
	}
}

//=============================================================================

class download extends content {
	
	public function __construct() {
		parent::__construct('download');
	}
}

//=============================================================================

// Create all content types
$g['content']['image']       = new image();
$g['content']['video']       = new video();
$g['content']['doc']         = new doc();
$g['content']['people']      = new people();
$g['content']['research']    = new research();
$g['content']['publication'] = new publication();
$g['content']['download']    = new download();

//=============================================================================

// Set referenced types

$g['content']['image']->displays = [
	'default' => [], 'teaser' => []
];

$g['content']['video']->displays = [
	'default' => [], 'teaser' => []
];

$g['content']['doc']->displays = [
	'default' => [], 'teaser' => []
];

$g['content']['people']->displays = [
	'default' => [
		'image'       => 'all',
		'research'    => 'all',
		'publication' => 'all'],
	'teaser' => [
		'image' => 'max']
];

$g['content']['research']->displays = [
	'default' => [
		'image'       => 'all',
		'video'       => 'all',
		'people'      => 'all',
		'publication' => 'all'],
	'teaser' => [
		'image' => 'max']
];

$g['content']['publication']->displays = [
	'default' => [
		'image'    => 'all',
		'video'    => 'all',
		'doc'      => 'all',
		'research' => 'all',
		'people'   => 'all'],
	'teaser' => [
		'doc'  => 'max',
		'video'  => 'max',
		'people' => 'all']
];

$g['content']['download']->displays = [
	'default' => [], 'teaser' => []
];

//=============================================================================

