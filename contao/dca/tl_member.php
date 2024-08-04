<?php

use Contao\Backend;
use Contao\MemberGroupModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\DataContainer;

// columns
$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['fields'] = array('rank');
$GLOBALS['TL_DCA']['tl_member']['list']['sorting']['flag'] = DataContainer::SORT_DESC;
$GLOBALS['TL_DCA']['tl_member']['list']['label']['fields'] = array('rank', 'firstname', 'lastname', 'role', 'groups');
$GLOBALS['TL_DCA']['tl_member']['list']['label']['label_callback'] = array('tl_member_extended', 'addInfo');

// remove operations
unset($GLOBALS['TL_DCA']['tl_member']['list']['operations']['su']);

// remove sorting
$GLOBALS['TL_DCA']['tl_member']['fields']['dateAdded']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['company']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['state']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['username']['sorting'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['currentLogin']['sorting'] = false;

// remove search
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['company']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['street']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['phone']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['mobile']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['fax']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['email']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['website']['search'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['username']['search'] = false;

// remove search
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['filter'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['filter'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['language']['filter'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['login']['filter'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['disable']['filter'] = false;

$GLOBALS['TL_DCA']['tl_member']['fields']['rank'] = array(
    'label' => array('Dienstgrad', 'Dienstgrad der Feuerwehr'),
    'filter' => true,
    'sorting' => true,
    'flag'  => DataContainer::SORT_BOTH,
    'options' => array(
        'Dienstgrade der Feuerwehrjugend' => array(
            '010_JFM1' => 'Jugendfeuerwehrmitglied (JFM1)',
            '020_JFM2' => 'Jugendfeuerwehrmitglied (JFM2)',
            '030_JFM3' => 'Jugendfeuerwehrmitglied (JFM3)',
            '040_JFM' => 'Jugendfeuerwehrmitglied (JFM)',
        ),
        'Mannschaftsdienstgrade' => array(
            '050_PFM' => 'Probefeuerwehrmann (PFM)',
            '060_FM' => 'Feuerwehrmann (FM)',
            '070_OFM' => 'Oberfeuerwehrmann (OFM)',
            '080_HFM' => 'Hauptfeuerwehrmann (HFM)'
        ),
        'Chargendienstgrade' => array(
            '090_LM' => 'Löschmeister (LM)',
            '100_OLM' => 'Oberlöschmeister (OLM)',
            '110_HLM' => 'Hauptlöschmeister (HLM)',
            '120_BM' => 'Brandmeister (BM)',
            '130_OBM' => 'Oberbrandmeister (OBM)',
            '140_HBM' => 'Hauptbrandmeister (HBM)'
        ),
        'Verwaltungsdienstgrade' => array(
            '150_V2' => 'Verwalter (V2)',
            '160_V3' => 'Verwalter (V3)',
            '170_OV' => 'Oberverwalter (OV)',
            '180_HV' => 'Hauptverwalter (HV)',
            '190_BV' => 'Bezirksverwalter (BV)'
        ),
        'Offiziersdienstgrade' => array(
            '200_BI' => 'Brandinspektor (BI)',
            '210_OBI' => 'Oberbrandinspektor (OBI)',
            '220_HBI' => 'Hauptbrandinspektor (HBI)',
            '230_FARZT' => 'Feuerwehrarzt (FARZT)',
            '240_FKUR' => 'Feuerwehrkurat (FKUR)',
            '250_FT' => 'Feuerwehrtechniker (FT)'
        ),
        'Höhere Offiziersdienstgrade' => array(
            '260_ABI' => 'Abschnittsbrandinspektor (ABI)',
            '270_BR' => 'Brandrat (BR)',
            '280_OBR' => 'Oberbrandrat (OBR)',
        ),
        'Stabsoffiziersdienstgrade' => array(
            '290_LBDSTV' => 'Landesbranddirektor-Stv (LBDSTV)',
            '300_LBD' => 'Landesbranddirektor (LBD)',
            '310_LFARZT' => 'Landesfeuerwehrarzt (LFARZT)',
            '320_LFKUR' => 'Landesfeuerwehrkurat (LFKUR)',
        ),
        'Dienstgrade der Feuerwehrinspektoren' => array(
            '330_BFI' => 'Bezirksfeuerwehrinspektor (BFI)',
            '340_LFI' => 'Landesfeuerwehrinspektor (LFI)'
        )
    ),
    'inputType' => 'select',
    'eval' => array('chosen'=>true, 'tl_class'=>'w25'),
    'sql' => "varchar(255) NOT NULL default 'FM'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['eval']['tl_class'] = 'w25';
$GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['eval']['mandatory'] = false;
$GLOBALS['TL_DCA']['tl_member']['fields']['lastname']['eval']['tl_class'] = 'w25';
$GLOBALS['TL_DCA']['tl_member']['fields']['lastname']['eval']['mandatory'] = false;

$GLOBALS['TL_DCA']['tl_member']['fields']['role'] = array(
    'label' => array('Funktion', 'Funktion in der Feuerwehr'),
    'inputType' => 'text',
    'search' => 'true',
    'eval' => array('tl_class' => 'w25'),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['image'] = array(
    'label' => array('Bild', 'Ein Profilbild hinzufügen'),
    'inputType' => 'fileTree',
    'eval' => array('filesOnly' => true, 'fieldType' => 'radio', 'tl_class' => 'clr w50'),
    'sql' => "binary(16) NULL"
);

PaletteManipulator::create()
    ->removeField('dateOfBirth', 'personal_legend')
    ->removeField('gender', 'personal_legend')
    ->removeField('language', 'personal_legend')
    ->removeField('company', 'address_legend')
    ->removeField('street', 'address_legend')
    ->removeField('postal', 'address_legend')
    ->removeField('city', 'address_legend')
    ->removeField('state', 'address_legend')
    ->removeField('country', 'address_legend')
    ->removeField('phone', 'contact_legend')
    ->removeField('mobile', 'contact_legend')
    ->removeField('email', 'contact_legend')
    ->removeField('website', 'contact_legend')
    ->removeField('fax', 'contact_legend')
    ->removeField('login', 'login_legend')
    ->removeField('assignDir', 'homedir_legend')
    ->removeField('disable', 'account_legend')
    ->removeField('start', 'account_legend')
    ->removeField('stop', 'account_legend')
    ->removeField('newsletter', 'newsletter_legend')
    ->applyToPalette('default', 'tl_member');

PaletteManipulator::create()
    ->addField('rank', 'firstname', PaletteManipulator::POSITION_BEFORE)
    ->addField('role', 'lastname')
    ->addField('image', 'role')
    ->applyToPalette('default', 'tl_member');

class tl_member_extended extends Backend
{

    public function addInfo($row, $label, DataContainer $dc, $args) {

        $options = $GLOBALS['TL_DCA']['tl_member']['fields']['rank']['options'];
        $format = '<img src="/files/theme/images/dienstgrade/%s.svg" width="16" height="16"> %s';

        foreach($options as $category => $ranks) {
            foreach($ranks as $rank => $name) {
                if($row['rank'] == $rank) {
                    $args[0] = sprintf($format, $rank, $name);
                }
            }
        }

        $groups = unserialize($row['groups']);
        $groupNames = array();

        if($groups && is_array($groups)) {

            foreach($groups as $id) {
                $groupNames[] = MemberGroupModel::findById($id)->name;
            }

            $args[4] = join(', ', $groupNames);
        }

        return $args;

    }

}
