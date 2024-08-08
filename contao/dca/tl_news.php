<?php

// didn't work :(
//$GLOBALS['TL_DCA']['tl_news']['fields']['id'] = array('sql' => "int(32) unsigned NOT NULL auto_increment");

// use enclosures as gallery
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['filesOnly'] = false;
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['isDownloads'] = false;
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['isGallery'] = true;
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['isSortable'] = true;
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['files'] = true;
$GLOBALS['TL_DCA']['tl_news']['fields']['enclosure']['eval']['extensions'] = '%contao.image.valid_extensions%';