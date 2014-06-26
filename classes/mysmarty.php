<?php

//-----------------------------------------------------------------------------

//namespace mycms;
require_once('classes/smarty/Smarty.class.php');
use mycms\system as system;

//-----------------------------------------------------------------------------

class mysmarty extends \Smarty
{
    public function __construct()
    {
		global $g;

		parent::__construct();
        $this->template_dir = '.';
        $this->compile_dir = 'templates_c';
        if ($g['runmode'] == 'debug')
			$this->caching = 0;

		$this->registerPlugin('function', 'vardump', 'vardump');   // vardump
        $this->registerPlugin('function', 'gl',      'gl');        // link generator
        $this->registerPlugin('function', 't',       't');         // translator
        $this->registerPlugin('function', 'txt2img', 'txt2img');   // text to image
        $this->registerPlugin('modifier', 'jdate',   'jdate');     // jalali date convertor
    }
}

//-----------------------------------------------------------------------------

function vardump($p, &$smarty){
    echo '<pre>';
    var_dump($p['var']);
    echo '</pre>';
}

//-----------------------------------------------------------------------------

function gl($p, &$smarty) {
    echo system::genlink($p['url']);
}

//-----------------------------------------------------------------------------

function txt2img($p, &$smarty) {
	$im = imagecreate(160, 16);

	// White background and blue text
	$bg = imagecolorallocate($im, 255, 255, 255);
	$textcolor = imagecolorallocate($im, 50, 50, 50);

	// Write the string at the top left
	imagestring($im, 2, 0, 0, $p['text'], $textcolor);

	ob_start();
		// Output the image
		//header('Content-type: image/jpeg');
		imagepng($im, NULL);
		$imgdata = ob_get_contents();
	ob_end_clean();

	imagedestroy($im);

	return 'data:image/png;base64,' . base64_encode($imgdata);
}

//-----------------------------------------------------------------------------

function t($p, &$smarty) {
    //echo $p['s'];
    if(isset($p['m']) && $p['m'] == false) {
        eval('echo L_' . str_replace(' ', '_', strtoupper($p['s'])) . ';');
    } else {
        eval('echo L_' . strtoupper($_GET['module']) . '_' . str_replace(' ', '_', strtoupper($p['s'])) . ';');
    }
}

//-----------------------------------------------------------------------------

function jdate($date, $format, $persian = true, $echo = true) {
    if(!$date) return;

    $ud = strtotime($date) ? strtotime($date) : system::persian_strtotime($date);

    $date = system::persian_strftime_utf($format, $ud, $persian);

    if($echo){
        echo $date;
    }else{
        return $date;
    }
}

//-----------------------------------------------------------------------------

//persian number
function pn($string, $echo = true)
{
    if(!$string) return;

    $string = system::persian_no($string);

    if($echo){
        echo $string;
    }else{
        return $string;
    }
}

//-----------------------------------------------------------------------------
