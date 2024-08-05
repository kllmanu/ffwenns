<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_content']['fields']['desc'] = array(
    'label' => array('Beschreibung', 'Kurze Beschreibung zur Überschrift'),
    'inputType' => 'text',
    'search' => 'true',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''"
);

PaletteManipulator::create()
    ->addField('desc', 'headline')
    ->applyToPalette('headline', 'tl_content');

$GLOBALS['TL_DCA']['tl_content']['fields']['name'] = array(
    'label' => array('Name', 'Name der Person/des Ereignisses'),
    'inputType' => 'text',
    'search' => 'true',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['time'] = array(
    'label' => array('Zeit', 'Zeitraum der Person/des Ereignisses'),
    'inputType' => 'text',
    'search' => 'true',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['text']['eval']['mandatory'] = false;