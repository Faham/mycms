<?php
/**
 * Table Definition for mycms_publication
 */
require_once 'DB/DataObject.php';

class Mycms_publication extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

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

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
