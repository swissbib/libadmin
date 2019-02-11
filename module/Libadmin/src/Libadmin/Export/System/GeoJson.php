<?php
namespace Libadmin\Export\System;

use Interop\Container\ContainerInterface;
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
     * GeoJson constructor.
     *
     * @param ContainerInterface $container container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
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
     * @return array
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
                        'properties' => $this->extractInstitutionData(
                            $institution, $group
                        ),
                        'geometry' => [
                            'coordinates' => [
                                $institution->retrieveLongitude(),
                                $institution->retrieveLatitude()
                            ],
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

        return [
            'bib_code' => $institution->getBib_code(),
            'group_code' => $group->getCode(),
            'group_label' => [
                'de' => $group->getLabel_de(),
                'fr' => $group->getLabel_fr(),
                'it' => $group->getLabel_it(),
                'en' => $group->getLabel_en()
            ],
            'address' => $addressData,
            //used to generate the library facet :
            'canton' => $this->extractCanton($institution),
            'label' => [
                'de' => $institution->getLabel_de(),
                'fr' => $institution->getLabel_fr(),
                'it' => $institution->getLabel_it(),
                'en' => $institution->getLabel_en()
            ],
            'website' => $institution->getWebsite(),
            'url' => [
                'de' => $institution->getUrl_de(),
                'fr' => $institution->getUrl_fr(),
                'it' => $institution->getUrl_it(),
                'en' => $institution->getUrl_en()
            ]
        ];
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
        //$favoriteOnly = $this->getOption('all') == true ? true : false;
        $favoriteOnly = true;

        return $this->institutionTable->getAllGroupViewInstitutions($idView, $idGroup, true, $favoriteOnly);
    }

    /**
     * Extract full address information from institution
     * With Canton and Country
     * If no address is available, return empty values
     *
     * @param Institution $institution the institution
     *
     * @return string canton
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
}
