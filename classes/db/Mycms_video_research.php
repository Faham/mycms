<?php
/**
 * Table Definition for mycms_video_research
 */
require_once 'DB/DataObject.php';

class Mycms_video_research extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_video_research';    // table name
    public $video_id;                        // int(11)  not_null primary_key
    public $research_id;                     // int(11)  not_null primary_key multiple_key
    public $video_order;                     // int(11)  
    public $research_order;                  // int(11)  

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
