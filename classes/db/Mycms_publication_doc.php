<?php
/**
 * Table Definition for mycms_publication_doc
 */
require_once 'DB/DataObject.php';

class Mycms_publication_doc extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'mycms_publication_doc';    // table name
    public $doc_id;                          // int(11)  not_null
    public $publication_id;                  // int(11)  not_null

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}