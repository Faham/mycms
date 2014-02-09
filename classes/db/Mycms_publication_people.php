<?php
/**
 * Table Definition for mycms_publication_people
 */
require_once 'DB/DataObject.php';

class Mycms_publication_people extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_publication_people';    // table name
    public $people_id;                       // int(11)  not_null
    public $publication_id;                  // int(11)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
