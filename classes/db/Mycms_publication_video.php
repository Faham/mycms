<?php
/**
 * Table Definition for mycms_publication_video
 */
require_once 'DB/DataObject.php';

class Mycms_publication_video extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_publication_video';    // table name
    public $video_id;                        // int(11)  not_null primary_key
    public $publication_id;                  // int(11)  not_null primary_key multiple_key

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
