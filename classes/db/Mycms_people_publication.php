<?php
/**
 * Table Definition for mycms_people_publication
 */
require_once 'DB/DataObject.php';

class Mycms_people_publication extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_people_publication';    // table name
    public $people_id;                       // int(11)  not_null primary_key
    public $publication_id;                  // int(11)  not_null primary_key
    public $people_order;                    // int(11)  
    public $publication_order;               // int(11)  

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
