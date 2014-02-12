<?php
/**
 * Table Definition for mycms_doc
 */
require_once 'DB/DataObject.php';

class Mycms_doc extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_doc';           // table name
    public $doc_id;                          // int(11)  not_null primary_key unique_key auto_increment
    public $doc_filename;                    // string(250)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}