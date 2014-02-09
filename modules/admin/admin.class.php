<?php

//-----------------------------------------------------------------------------

namespace mycms;

//-----------------------------------------------------------------------------

class pages {

//-----------------------------------------------------------------------------

    public static function get_imagelist($shuffle = false, $limit = 50) {
        global $g;

		$q = '
			(SELECT p.people_id as id, "people" AS type, CONCAT_WS(" ", p.people_firstname, p.people_middlename, p.people_lastname) as title, image.image_filename as image
			FROM !!!people as p
			INNER JOIN (SELECT MAX(image_id) as image_id, people_id FROM !!!image_people GROUP BY people_id) as i
			ON p.people_id = i.people_id
			LEFT JOIN !!!image as image
			ON i.image_id =  image.image_id)
			UNION
			(SELECT p.publication_id as id, "publication" AS type, p.publication_title, image.image_filename as image
			FROM !!!publication as p
			INNER JOIN (SELECT MAX(image_id) as image_id, publication_id FROM !!!image_publication GROUP BY publication_id) as i
			ON p.publication_id = i.publication_id
			LEFT JOIN !!!image as image
			ON i.image_id =  image.image_id)
			UNION
			(SELECT r.research_id as id, "research" AS type, r.research_title, image.image_filename as image
			FROM !!!research as r
			INNER JOIN (SELECT MAX(image_id) as image_id, research_id FROM !!!image_research GROUP BY research_id) as i
			ON r.research_id = i.research_id
			LEFT JOIN !!!image as image
			ON i.image_id =  image.image_id)
		';

		$r = $shuffle? 'ORDER BY RAND()': '';
		$q = "SELECT * FROM ($q) AS t $r LIMIT $limit";

		$r = $g['db']->query($q);

		return $r;
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------

