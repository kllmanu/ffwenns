<?php

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

$GLOBALS['TL_DCA']['tl_content']['palettes']['timeline'] = '{type_legend},type;{chronik_legend},name,time;{text_legend},text;{image_legend},addImage;';