<?php

//=============================================================================

namespace mycms;
require_once 'classes/content.php';
global $g;

//=============================================================================

class image extends content {

	public $ext	     = array("gif", "jpeg", "jpg", "png");
	public $mime	 = array("image/gif", "image/jpeg", "image/jpg", "image/png");
	public $max_size = 1048576; // 1 * 1024 * 1024;

	public $__table = 'mycms_image';	// table name
	public $image_id;					// int(11)  not_null primary_key unique_key auto_increment
	public $image_filename;				// string(250)  not_null

	public $displays = array(
		'default' => array(),
		'teaser'  => array(),
	);

    public $references = array();

	public function __construct() {
		parent::__construct('image');
	}
}
$g['content']['image'] = new image();

//=============================================================================

class video extends content {

    public $ext      = array("mp4", "webm", "ogg");
    public $mime     = array("video/mp4", "video/webm", "video/ogg");
    public $max_size = 10485760; // 10 * 1024 * 1024;

	public $__table = 'mycms_video';	// table name
	public $video_id;					// int(11)  not_null primary_key unique_key auto_increment
	public $video_filename;				// string(250)  not_null

	public $displays = array(
		'default' => array(),
		'teaser'  => array()
	);

    public $references = array();

	public function __construct() {
		parent::__construct('video');
	}
}
$g['content']['video'] = new video();

//=============================================================================

class doc extends content {

    public $ext      = array("pdf");
    public $mime     = array("application/pdf");
    public $max_size = 1048576; // 1 * 1024 * 1024;

	public $__table = 'mycms_doc';		   // table name
	public $doc_id;						  // int(11)  not_null primary_key unique_key auto_increment
	public $doc_filename;					// string(250)  not_null

	public $displays = array(
		'default' => array(),
		'teaser'  => array()
	);

    public $references = array();

	public function __construct() {
		parent::__construct('doc');
	}
}
$g['content']['doc'] = new doc();

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

	public $__table = 'mycms_people';	// table name
	public $people_id;					// int(11)  not_null primary_key unique_key auto_increment
	public $people_firstname;			// string(50)  not_null
	public $people_middlename;			// string(50)
	public $people_lastname;			// string(50)  not_null
	public $people_affiliation;			// string(100)
	public $people_email;				// string(50)
	public $people_bio;					// string(1000)
    public $people_group;               // string(21)  not_null enum
	public $people_nsid;				// string(6)
	public $people_start;				// date(10)  binary
	public $people_end;					// date(10)  binary

	public $field_type = array(
		'people_id'		     => 'int:0,',
		'people_firstname'   => 'alphabetic',
		'people_middlename'  => 'alphabetic',
		'people_lastname'	 => 'alphabetic',
		'people_affiliation' => 'string',
		'people_email'	     => 'email',
        'people_bio'         => 'string',
		'people_nsid'		 => 'string',
		'people_group'	     => 'enum:faculty,adjunct_faculty,researcher,graduate_student,staff,alumni,recent_visitor,undergraduate_student,other',
		'people_start'	     => 'date:Y-m-d',
		'people_end'		 => 'date:Y-m-d',
	);

	public $references = array(
		'image',
		'research',
		'publication',
	);

	public $displays = array(
		'default' => array(
			'image'	   => 'all',
			'research'	=> 'all',
			'publication' => 'all'),
		'teaser' => array(
			'image' => 'max'),
	);

	public $title_format = 'people_firstname people_middlename people_lastname';

	public function __construct() {
		parent::__construct('people');
	}
}
$g['content']['people'] = new people();

//=============================================================================

class research extends content {

	public $__table = 'mycms_research';	 // table name
	public $research_id;				 // int(11)  not_null primary_key unique_key auto_increment
	public $research_title;				 // string(150)  not_null
	public $research_summary;			 // string(300)
	public $research_description;		 // string(1500)
	public $research_status;			 // string(7)  not_null enum
	public $research_priority;			 // string(1)  not_null enum

    public $field_type = array(
        'research_id'          => 'int:0,',
        'research_title'       => 'string',
        'research_summary'     => 'string',
        'research_description' => 'string',
        'research_status'      => 'enum:active,future,onhold,past,unknown',
        'research_priority'    => 'int:0,',
    );

    public $references = array(
        'image',
        'video',
        'people',
        'publication',
    );

	public $displays = array(
		'default' => array(
			'image'	   => 'all',
			'video'	   => 'all',
			'people'	  => 'all',
			'publication' => 'all'),
		'teaser' => array(
			'image' => 'max'),
		'tiny' => array(
			'research_title',
			'image' => 'max'),
	);

    public $title_format = 'research_title';

	public function __construct() {
		parent::__construct('research');
	}
}
$g['content']['research'] = new research();

//=============================================================================

class publication extends content {

	public $__table = 'mycms_publication';	   // table name
    public $publication_id;                    // int(11)  not_null primary_key unique_key auto_increment
    public $publication_type;                  // string(13)  not_null enum
    public $publication_title;                 // string(300)  not_null
    public $publication_booktitle;             // string(300)
    public $publication_abstract;              // string(2000)  not_null
    public $publication_year;                  // year(4)  not_null unsigned zerofill
    public $publication_month;                 // string(9)  enum
    public $publication_toappear;              // int(1)  not_null
    public $publication_volume;                // string(20)
    public $publication_number;                // string(20)
    public $publication_series;                // string(30)
    public $publication_address;               // string(50)
    public $publication_pages;                 // string(20)
    public $publication_doi_number;            // string(20)
    public $publication_note;                  // string(20)
    public $publication_journal;               // string(200)
    public $publication_isbn;                  // string(20)
    public $publication_edition;               // string(20)
    public $publication_chapter;               // string(20)
    public $publication_technumber;            // string(20)
    public $publication_school;                // string(50)
    public $publication_howpublished;          // string(50)
    public $publication_institution;           // string(50)
    public $publication_organization;          // string(50)
    public $publication_publisher;             // string(50)
    public $publication_url;                   // string(300)
    public $publication_temp_pdflink;          // string(300)
    public $publication_temp_secondarylink;    // string(300)

    public $field_type = array(
        'publication_type'         => 'enum:article,book,booklet,conference,inbook,incollection,inproceedings,manual,mastersthesis,misc,phdthesis,proceedings,techreport,unpublished',
        'publication_title'        => 'string',
        'publication_booktitle'    => 'string',
        'publication_abstract'     => 'string',
        'publication_year'         => 'int',
        'publication_month'        => 'enum:january,february,march,april,may,june,july,august,september,october,november,december',
        'publication_toappear'     => 'int:0,1',
        'publication_volume'       => 'string',
        'publication_number'       => 'string',
        'publication_series'       => 'string',
        'publication_address'      => 'string',
        'publication_pages'        => 'string',
        'publication_doi_number'   => 'string',
        'publication_note'         => 'string',
        'publication_journal'      => 'string',
        'publication_isbn'         => 'string',
        'publication_edition'      => 'string',
        'publication_chapter'      => 'string',
        'publication_technumber'   => 'string',
        'publication_school'       => 'string',
        'publication_howpublished' => 'string',
        'publication_institution'  => 'string',
        'publication_organization' => 'string',
        'publication_publisher'    => 'string',
        'publication_url'          => 'string',
    );

    public $references = array(
        'image',
        'video',
        'doc',
        'research',
        'people',
    );

	public $displays = array(
		'default' => array(
			'image'	   => 'all',
			'video'	   => 'all',
			'doc'	   => 'all',
			'research' => 'all',
			'people'   => 'all'),
		'teaser' => array(
			'doc'    => 'max',
			'video'  => 'max',
			'people' => 'all'),
	);

    public $title_format = 'publication_title';

	public function __construct() {
		parent::__construct('publication');
	}
}
$g['content']['publication'] = new publication();

//=============================================================================

class course extends content {

	public function __construct() {
		parent::__construct('course');
	}
}
$g['content']['course'] = new course();

//=============================================================================

class download extends content {

	public $displays = array(
		'default' => array(),
		'teaser' => array()
	);

	public function __construct() {
		parent::__construct('download');
	}
}
$g['content']['download'] = new download();

//=============================================================================

