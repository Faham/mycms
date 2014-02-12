<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB/DataObject.php';

//-----------------------------------------------------------------------------

abstract class content extends \DB_DataObject {

//-----------------------------------------------------------------------------

	protected $m_type = 'unknown';
	public $displays  = array();

//-----------------------------------------------------------------------------

	public function __construct($type) {
		$this->m_type = $type;
	}

//-----------------------------------------------------------------------------

	public function create() {
	}

//-----------------------------------------------------------------------------

	public function edit() {
	}

//-----------------------------------------------------------------------------

	public function getall() {
		global $g;

	    $data_obj = \DB_DataObject::Factory('mycms\\' . $this->m_type);

	    if ($data_obj instanceof \DB_DataObject_Error) {
	    	$g['error']->push($data_obj->message);
	    	return;
	    }

		$count = $data_obj->find();
		$rows = array();

	    while ($data_obj->fetch()) {
	        $rows[] = clone($data_obj);
	    }

	    return ['rows'  => $rows,
	    		'count' => $count];
	}

//-----------------------------------------------------------------------------

    public function view($display = 'teaser', $where = '', $sortby = '', $limit = '0,99', $get_referenced_data = true) {
        global $g;

		if (!array_key_exists($display, $this->displays)) {
			$r = ['error' => true, 'count' => 0, 'rows' => array()];
			$g['error']->push("Content type " . $this->m_type . " does not support $display display.", 'error');
			return $r;
		}

		$t = $this->m_type;
		$q = '';

		$w = "!!!$t";
		if (!empty($where))
			$w = "(SELECT * FROM $w as $t WHERE $where)";

		$col_reftypes = '';
		$q            = '';

		foreach ($this->displays[$display] as $reftype => $func){
			if ($func == 'all') {
				$q = "$q LEFT JOIN (
						SELECT pt.{$t}_id, GROUP_CONCAT(DISTINCT pt.{$reftype}_id ORDER BY pt.{$reftype}_id ASC SEPARATOR ',') as $reftype
						FROM !!!{$reftype}_{$t} as pt
						GROUP BY pt.{$t}_id) as ref_{$reftype}
						ON $t.{$t}_id = ref_{$reftype}.{$t}_id
				";
				$col_reftypes = "$col_reftypes, ref_{$reftype}.$reftype AS $reftype";
			} else if ($func == 'max' || $func == 'min') {
				$q = "$q LEFT JOIN (
							SELECT pt.{$t}_id, $func(pt.{$reftype}_id) as {$reftype}_id
							FROM !!!{$reftype}_{$t} as pt
							GROUP BY pt.{$t}_id
						) as ref_{$reftype}
						ON $t.{$t}_id = ref_{$reftype}.{$t}_id
						LEFT JOIN !!!{$reftype} AS {$reftype} ON ref_{$reftype}.{$reftype}_id = $reftype.{$reftype}_id

				";
				$col_reftypes = "$col_reftypes, {$reftype}.*";
			}

		}

		$q = "SELECT $t.*{$col_reftypes} \n FROM $w as $t \n $q";

		if (!empty($sortby))
			$q = "$q ORDER BY $sortby";

		$q = "$q LIMIT $limit";

		$r = $g['db']->query($q);

		// Query referenced data types and replace referenced node indices with actual referenced node data;
		if ($get_referenced_data) {

			$refdata = array();
			$refindices = array();

			foreach ($this->displays[$display] as $ref => $func)
				if ($func == 'all') {
					$refdata[$ref] = null;
					$refindices[$ref] = '';
				}

			foreach ($refdata as $rt => &$rd) {
				foreach ($r['rows'] as &$row)
					if ($row[$rt] != null)
						$refindices[$rt] .= (empty($refindices[$rt])?'':',') . $row[$rt];

				if (!empty($refindices[$rt]))
					$rd = $g['content'][$rt]->view('teaser', "$rt.{$rt}_id IN (" . $refindices[$rt] . ")", "$rt.{$rt}_id ASC", '0,99', $display == 'teaser'? false: true);
			}

			// Find and put each records data from $refdata into $r;
			foreach ($r['rows'] as &$row) {
				foreach ($this->displays[$display] as $rt => $func) {
					if (empty($row[$rt]) || $func != 'all')
						continue;

					if (substr_count($row[$rt], ',') > 0)
						$inds = explode(',', $row[$rt]);
					else
						$inds = [$row[$rt]];

					$row[$rt] = array('rows' => array(), 'count' => 0);
					if ($refdata[$rt]['count'] > 0)
						foreach ($refdata[$rt]['rows'] as &$rw) {
							$v = array_search($rw["{$rt}_id"], $inds);

							if ($v !== false) {
								$row[$rt]['rows'][] = &$rw;
								$row[$rt]['count']++;
							}
						}
				}
			}
		}

        return $r;
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
