<?php
/**
 * Table Definition for mycms_people
 */
require_once 'DB/DataObject.php';

class Mycms_people extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

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

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
