<?php
namespace Libadmin\Export\System;

use Interop\Container\ContainerInterface;
use Libadmin\Model\Institution;
use Libadmin\Table\InstitutionRelationTable;
use Zend\View\Model\JsonModel;
use Zend\Db\ResultSet\ResultSetInterface;
use Libadmin\Model\Group;
use Zend\I18n\Translator\Translator;

/**
 * VuFind Export system
 */
class GeoJson extends System
{
    /**
     * Institution Relation Table
     *
     * @var InstitutionRelationTable $institutionRelationTable InstitRelation Table
     */
    protected $institutionRelationTable;

    /**
     * Translator
     *
     * @var Translator $translator translator
     */
    protected $translator;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->translator = $this->getServiceLocator()->get('translator');

    }

    /**
     * @override
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->institutionRelationTable = $this->tablePluginManager->get('Libadmin\Table\InstitutionRelationTable');
    }

    /**
     * Get vufind json data
     *
     * @return JsonModel
     */
    public function getJsonData()
    {
        try {
            $data = [
                'type' => 'FeatureCollection',
                'features' => $this->getJsonPayloadData()
            ];
        } catch (\Exception $e) {
            $data = [
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ];
        }

        return new JsonModel($data);
    }

    /**
     * Get grouped institution data
     *
     * @return Array
     */
    protected function getJsonPayloadData()
    {
        $data = [];
        $groups = $this->getGroups();


        foreach ($groups as $group) {

            $institutions = $this->getGroupInstitutions($group);

            foreach ($institutions as $institution) {
                try {
                    $data[] = [
                        'type' => 'Feature',
                        'properties' => $this->extractInstitutionData($institution, $group),
                        'geometry' => [
                            'coordinates' => [$institution->getLongitude(), $institution->getLatitude()],
                            'type' => 'Point',
                        ]
                    ];
                } catch(\Exception $e) {
                    //for example no coordinates
                }

            }
        }

        return $data;
    }

    /**
     * Extract required data from institution
     *
     * @param Institution $institution
     *
     * @return array
     */
    protected function extractInstitutionData(Institution $institution, Group $group)
    {
        $addressData = $this->extractAddressData($institution);

        return array(
            'bib_code' => $institution->getBib_code(),
            'group_code' => $group->getCode(),
            'group_label' => array(
                'de' => $group->getLabel_de(),
                'fr' => $group->getLabel_fr(),
                'it' => $group->getLabel_it(),
                'en' => $group->getLabel_en()
            ),
            'address' => $addressData,
            'canton' => $this->extractCanton($institution),
            'label' => array(
                'de' => $institution->getLabel_de(),
                'fr' => $institution->getLabel_fr(),
                'it' => $institution->getLabel_it(),
                'en' => $institution->getLabel_en()
            ),
            'website' => $institution->getWebsite(),
            'url' => array(
                'de' => $institution->getUrl_de(),
                'fr' => $institution->getUrl_fr(),
                'it' => $institution->getUrl_it(),
                'en' => $institution->getUrl_en()
            )
        );
        return $data;
    }

    /**
     * Get groups for view
     *
     * @return Group[]|ResultSetInterface|null
     */
    protected function getGroups()
    {
        $idView = $this->getView()->getId();
        return $this->groupTable->getViewGroups($idView);
    }

    /**
     * Get institutions for group in view
     *
     * @param    Group    $group
     * @return    Institution[]|ResultSetInterface|null
     */
    protected function getGroupInstitutions(Group $group)
    {
        $idView = $this->getView()->getId();
        $idGroup = $group->getId();
        $favoriteOnly = $this->getOption('all') == true ? true : false;

        return $this->institutionTable->getAllGroupViewInstitutions($idView, $idGroup, true, $favoriteOnly);
    }

    /**
     * Extract required data from group
     *
     * @param    Group    $group
     * @return   array
     */
    protected function extractGroupData(Group $group)
    {
        return ['group' => $group->getLabel_de()];
    }

    /**
     * Extract full address information from institution
     * With Canton and Country
     * If no address is available, return empty values
     *
     * @param    Institution        $institution
     * @return   array
     */
    protected function extractCanton(Institution $institution)
    {
        //todo : not really efficient, would be better to make a join in the initial query
        $institutionWithAddress = $this->institutionTable->getRecord($institution->getId());
        if($institutionWithAddress->getId_postadresse()) {
            $postAdresse = $institutionWithAddress->getPostadresse();

            return $postAdresse->getCanton();
        } else {
            return "";
        }
    }

    /**
     * Return colors of the group (network)
     *
     * @param Group $group the group (i.e. network)
     *
     * @return string the color in RGB hex color
     */
    protected function getColor(Group $group)
    {
        switch ($group->getCode()) {
        case 'ABN':
            return '000000';
            break;
        case 'ALEX':
            return '990000';
            break;
        case 'BISCH':
            return '3399FF';
            break;
        case 'BGR':
            return '000066';
            break;
        case 'CEO':
            return 'FF0000';
            break;
        case 'HEMU':
            return '66FFFF';
            break;
        case 'IDSBB':
            return 'FF8000';
            break;
        case 'IDSLU':
            return '66B2FF';
            break;
        case 'IDSSG':
            return '006600';
            break;
        case 'KBTGV':
            return '99FFF';
            break;
        case 'LIBIB':
            return 'CCE5FF';
            break;
        case 'SNL':
            return 'FF3333';
            break;
        case 'NEBIS':
            return '0000FF';
            break;
        case 'VAUD':
            return '99004C';
            break;
        case 'RERO':
            return '66B2FF';
            break;
        case 'SBT':
            return 'CCE5FF';
            break;
        case 'SGBN':
            return '00FF00';
            break;
        default:
            return '808080';
        }
    }

    protected function getTranslatedText($text) {
        $lang = $this->getOption('lang');
        switch ($lang) {
        case 'de':
            $locale='de_DE';
            break;
        case 'fr':
            $locale='fr_CH';
            break;
        default:
            $locale='de_DE';
        }
        return $this->translator->translate($text, 'default', $locale);
    }

    protected function getTranslatedInstitutionLabel(Institution $institution)
    {
        $lang = $this->getOption('lang');
        switch ($lang) {
        case 'de':
            return $institution->getLabel_de();
        case 'fr':
            return $institution->getLabel_fr();
        default:
            return $institution->getLabel_de();
        }
    }

    protected function getTranslatedGroupLabel(Group $group)
    {
        $lang = $this->getOption('lang');
        switch ($lang) {
            case 'de':
                return $group->getLabel_de();
            case 'fr':
                return $group->getLabel_fr();
            default:
                return $group->getLabel_de();
        }
    }
}
