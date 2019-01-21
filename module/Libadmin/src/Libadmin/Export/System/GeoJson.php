<?php
namespace Libadmin\Export\System;

use Libadmin\Model\Institution;
use Libadmin\Table\InstitutionRelationTable;
use Zend\View\Model\JsonModel;
use Zend\Db\ResultSet\ResultSetInterface;
use Libadmin\Model\Group;

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
                    //institution
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
        $data = [
            'label-de' =>  $institution->getLabel_de(),
            'network' => $group->getLabel_de(),
            'documents from this library' => '<a href="https://www.swissbib.ch/Search/Results?lookfor=&type=AllFields&filter%5B%5D=institution%3A%22'. $institution->getBib_code() .'%22">link</a>',
            'marker-symbol' => 'library',
            'marker-color' => $this->getColor($group),
        ];
        if (!empty($institution->getWebsite())) {
            $data['website'] = $institution->getWebsite();
        }
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
        case 'NEBIS':
            return '0000FF';
            break;
        case 'ABN':
            return 'FF0000';
            break;
        default:
            return '808080';
        }
    }
}
