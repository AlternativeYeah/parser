<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 19.12.2017
 * Time: 2:29
 */

namespace AppBundle\Service\Sort;

use AppBundle\Model\Apartment;

class ApartmentSort implements SortInterfase
{
    public function sortByPrice(array $items, $sort = 'ASC'){
        $data = array();
        foreach ($items as $item){
            /** @var Apartment $item */
            $data[$item->getPrice()] = $item;
        }
        if($sort === self::DESC){
             krsort($data);
        }else{
            ksort($data);
        }
        return $data;
    }
}