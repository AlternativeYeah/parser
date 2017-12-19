<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 17.12.2017
 * Time: 21:20
 */

namespace AppBundle\Model;


class Seller
{
    const SELLER_TYPE_AGENT = 'agent';
    const SELLER_TYPE_OWNER = 'owner';

    /**
     * @var string
     */
    private $name;

    /**
     * @var
     */
    private $type;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}