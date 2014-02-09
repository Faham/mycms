<?php
/**
 * Table Definition for mycms_people_image
 */
require_once 'DB/DataObject.php';

class Mycms_people_image extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_people_image';    // table name
    public $image_id;                        // int(11)  not_null
    public $people_id;                       // int(11)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
