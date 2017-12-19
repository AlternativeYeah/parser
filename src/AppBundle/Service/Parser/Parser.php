<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 19.12.2017
 * Time: 1:44
 */

namespace AppBundle\Service\Parser;

interface Parser
{
    public function parse($city, $adType, $buildType, $limit);
}