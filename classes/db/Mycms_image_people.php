<?php
/**
 * Table Definition for mycms_image_people
 */
require_once 'DB/DataObject.php';

class Mycms_image_people extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_image_people';    // table name
    public $image_id;                        // int(11)  not_null primary_key
    public $people_id;                       // int(11)  not_null primary_key
    public $image_order;                     // int(11)  
    public $people_order;                    // int(11)  

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
