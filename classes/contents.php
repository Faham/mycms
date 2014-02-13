<?php

//=============================================================================

namespace mycms;
require_once 'classes/content.php';
global $g;

//=============================================================================

class image extends content {

    public $ext      = ["gif", "jpeg", "jpg", "png"];
    public $mime     = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
    public $max_size = 409600; // 400 * 1024;

    public $__table = 'mycms_image';         // table name
    public $image_id;                        // int(11)  not_null primary_key unique_key auto_increment
    public $image_filename;                  // string(250)  not_null
	public function __construct() {
		parent::__construct('image');
	}
}

//=============================================================================

class video extends content {

    public $__table = 'mycms_video';         // table name
    public $video_id;                        // int(11)  not_null primary_key unique_key auto_increment
    public $video_filename;                  // string(250)  not_null
	public function __construct() {
		parent::__construct('video');
	}
}

//=============================================================================

class doc extends content {

    public $__table = 'mycms_doc';           // table name
    public $doc_id;                          // int(11)  not_null primary_key unique_key auto_increment
    public $doc_filename;                    // string(250)  not_null
	public function __construct() {
		parent::__construct('doc');
	}
}

//=============================================================================

/*

int:min,max
alphabetic
alphabetic-utf8
email
string:regex_pattern
enum:val1,val2...
date:string_format

*/

class people extends content {

    public $__table = 'mycms_people';        // table name
    public $people_id;                       // int(11)  not_null primary_key unique_key auto_increment
    public $people_firstname;                // string(50)  not_null
    public $people_middlename;               // string(50)
    public $people_lastname;                 // string(50)  not_null
    public $people_affiliation;              // string(100)
    public $people_email;                    // string(50)
    public $people_bio;                      // string(1000)
    public $people_group;                    // string(21)  not_null enum
    public $people_start;                    // date(10)  binary
    public $people_end;                      // date(10)  binary

    public $field_type = [
        'people_id'          => 'int:0,',
        'people_firstname'   => 'alphabetic',
        'people_middlename'  => 'alphabetic',
        'people_lastname'    => 'alphabetic',
        'people_affiliation' => 'string',
        'people_email'       => 'email',
        'people_bio'         => 'string',
        'people_group'       => 'enum:faculty,adjunct_faculty,researcher,graduate_student,undergraduate_student,alumni,recent_visitor',
        'people_start'       => 'date:Y-m-d',
        'people_end'         => 'date:Y-m-d'
    ];

    public $references = [
        'image',
        'video',
        'doc',
        'research',
        'publication'
    ];

	public function __construct() {
		parent::__construct('people');
	}
}

//=============================================================================

class research extends content {

    public $__table = 'mycms_research';      // table name
    public $research_id;                     // int(11)  not_null primary_key unique_key auto_increment
    public $research_title;                  // string(150)  not_null
    public $research_summary;                // string(300)
    public $research_description;            // string(1500)
    public $research_status;                 // string(7)  not_null enum
    public $research_priority;               // string(1)  not_null enum
	public function __construct() {
		parent::__construct('research');
	}
}

//=============================================================================

class publication extends content {

    public $__table = 'mycms_publication';    // table name
    public $publication_id;                  // int(11)  not_null primary_key unique_key auto_increment
    public $publication_type;                // string(13)  not_null enum
    public $publication_title;               // string(300)  not_null
    public $publication_booktitle;           // string(300)
    public $publication_abstract;            // string(2000)  not_null
    public $publication_year;                // year(4)  not_null unsigned zerofill
    public $publication_month;               // string(9)  enum
    public $publication_toappear;            // int(1)  not_null
    public $publication_volume;              // int(11)
    public $publication_issuenum;            // int(11)
    public $publication_series;              // string(30)
    public $publication_address;             // string(50)
    public $publication_pages;               // int(11)
    public $publication_doi_number;          // string(20)
    public $publication_note;                // int(200)
    public $publication_journal;             // string(200)
    public $publication_isbn;                // string(20)
    public $publication_edition;             // int(11)
    public $publication_chapter;             // int(11)
    public $publication_technumber;          // string(20)
    public $publication_school;              // string(50)
    public $publication_howpublished;        // string(50)
    public $publication_institution;         // string(50)
    public $publication_organization;        // string(50)
    public $publication_publisher;           // string(50)
    public $publication_url;                 // string(300)
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

