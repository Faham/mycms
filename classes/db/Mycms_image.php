<?php
/**
 * Table Definition for mycms_image
 */
require_once 'DB/DataObject.php';

class Mycms_image extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_image';         // table name
    public $image_id;                        // int(11)  not_null primary_key unique_key auto_increment
    public $image_filename;                  // string(250)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
