<?php

namespace Libadmin\Services\View;

use Traversable;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
use Zend\Json\Json;

class FormetaModel extends ViewModel
{

    /**
     * Formeta is usually terminal
     *
     * @var bool
     */
    protected $terminate = true;


    /**
     * Serialize to JSON
     *
     * @return string
     */
    public function serialize()
    {
        $variables = $this->getVariables();
        if ($variables instanceof Traversable) {
            $variables = ArrayUtils::iteratorToArray($variables);
        }

        //return Json::encode($variables);

        $formetaString = null;

        foreach ($variables['data'] as $groupContainer) {
            //$groupString = "";
            $groupString  =  $groupContainer['group']['code'] . ' {';
            foreach ($groupContainer['group'] as $groupKey => $groupValue) {
                if (!is_array($groupValue)) {
                    //$groupString .= ' \'' . $groupKey . '\' : \'' . preg_replace(array('/\'/i'),array('\\'),$groupValue)  . ' \',';
                    $groupString .= ' \'' . $groupKey . '\' : \'' . preg_replace(array('/\'/i','/\\\\V/i','/\\\\E/i','/d\\\\a/i','/d\\\\i/i'),
                                    array('\\\'','E','d a','d\''),$groupValue)  . ' \',';
                } else {
                    $groupString .=   $this->serializeEntity($groupKey,$groupValue) ;
                }

            }
            $formetaString .= $groupString;


            $groupString =  'institutions {';

            foreach ($groupContainer['institutions'] as $institutionsContainer) {
                foreach ($institutionsContainer as $instKey => $instValue) {
                    if (!is_array($instValue)) {

                        //$groupString .= ' \'' . $instKey . '\' : \'' . preg_replace(array('/\'/i'),array('\\'),$instValue) . ' \',';
                        $groupString .= ' \'' . $instKey . '\' : \'' . preg_replace(array('/\'/i','/\\\\V/i','/\\\\E/i','/d\\\\a/i','/d\\\\i/i'),
                                array('\\\'','V','E','d a','d\''),$instValue) . '\',';
                    } else {

                        $groupString .= ' ' . $this->serializeEntity($instKey, $instValue) ;

                    }
                }
            }
            $formetaString .= $groupString;

            $formetaString .=  '} '; // schliesse Institutions
            $formetaString .= '}, ';
        }

        return $formetaString;

    }


    private function serializeEntity ($key, array $entity) {

        $localString =  ' ' . $key . ' {';

        foreach ($entity as $entityKey => $entityValue)
        {
            if (!is_array($entityValue)) {
                //$localString .= '\'' .  $entityKey . '\' : \'' . preg_replace(array('/\'/i'),array('\\'),$entityValue) . ' \',';
                $localString .= '\'' .  $entityKey . '\' : \'' . preg_replace(array('/\'/i','/\\\\V/i','/\\\\E/i','/d\\\\a/i','/d\\\\i/i'),
                        array('\\\'','V','E','d a','d\''),$entityValue) . '\',';
            } else {
                //$localString .=   ' {'  . $this->serializeEntity($entityKey,$entityValue) . ' }, ';
                $localString .=   ' '  . $this->serializeEntity($entityKey,$entityValue) . ', ';
            }
            //if (!is_array($groupLiteral))
        }

        $localString .=   '}, ';

        return $localString;

    }
}
