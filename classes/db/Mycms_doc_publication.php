<?php
/**
 * Table Definition for mycms_doc_publication
 */
require_once 'DB/DataObject.php';

class Mycms_doc_publication extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_doc_publication';    // table name
    public $doc_id;                          // int(11)  not_null primary_key
    public $publication_id;                  // int(11)  not_null primary_key
    public $doc_order;                       // int(11)  
    public $publication_order;               // int(11)  

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
