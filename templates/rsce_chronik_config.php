<?php

return array(
    'label' => array('Chronik', 'Eine Liste von Personen/Ereignissen erstellen'),
    'types' => array('content'),
    'contentCategory' => 'texts',
    'standardFields' => array('headline', 'cssID'),
    'fields' => array(
        'entries' => array(
            'label' => array('Einträge'),
            'elementLabel' => 'Eintrag #%s',
            'inputType' => 'list',
            'fields' => array(
                'image' => array(
                    'label' => array('Bild'),
                    'inputType' => 'fileTree',
                    'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'tl_class' => 'w25'),
                ),
                'name' => array(
                    'label' => array('Name'),
                    'inputType' => 'text',
                    'eval' => array('tl_class' => 'clr w50')
                ),
                'time' => array(
                    'label' => array('Zeit'),
                    'inputType' => 'text',
                    'eval' => array('tl_class' => 'w50')
                ),
                'description' => array(
                    'label' => array('Beschreibung'),
                    'inputType' => 'text',
                    'eval' => array('tl_class' => 'clr')
                ),
            )
        )
    )
);