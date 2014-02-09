<?php
/**
 * Table Definition for mycms_video
 */
require_once 'DB/DataObject.php';

class Mycms_video extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_video';         // table name
    public $video_id;                        // int(11)  not_null primary_key unique_key auto_increment
    public $video_filename;                  // string(250)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
