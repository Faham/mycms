<?php
/**
 * Table Definition for mycms_research
 */
require_once 'DB/DataObject.php';

class Mycms_research extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_research';      // table name
    public $research_id;                     // int(11)  not_null primary_key unique_key auto_increment
    public $research_title;                  // string(150)  not_null
    public $research_summary;                // string(300)  
    public $research_description;            // string(1500)  
    public $research_status;                 // string(7)  not_null enum
    public $research_priority;               // int(10)  unsigned

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
