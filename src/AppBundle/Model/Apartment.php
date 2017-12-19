<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 17.12.2017
 * Time: 16:14
 */

namespace AppBundle\Model;

class Apartment extends BaseModel
{
    const AD_TYPE_SALE = 'sale';
    const AD_TYPE_LEASE = 'lease';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $adType;

    /**
     * @var string
     */
    private $buildType;

    /**
     * @var int
     */
    private $roomsCount;

    /**
     * @var int
     */
    private $floor;

    /**
     * @var $int
     */
    private $BuildFloor;

    /**
     * @var float
     */
    private $livingArea;

    /**
     * @var float
     */
    private $area;

    /**
     * @var array
     */
    private $metro;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Address
     */
    private $address;

    /**
     * @var Seller
     */
    private $seller;

    /**
     * @var string
     */
    private $addressString;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getAdType()
    {
        return $this->adType;
    }

    /**
     * @param string $adType
     */
    public function setAdType($adType)
    {
        $this->adType = $adType;
    }

    /**
     * @return string
     */
    public function getBuildType()
    {
        return $this->buildType;
    }

    /**
     * @param string $buildType
     */
    public function setBuildType($buildType)
    {
        $this->buildType = $buildType;
    }

    /**
     * @return int
     */
    public function getRoomsCount()
    {
        return $this->roomsCount;
    }

    /**
     * @param int $roomsCount
     */
    public function setRoomsCount($roomsCount)
    {
        $this->roomsCount = $roomsCount;
    }

    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    }

    /**
     * @return mixed
     */
    public function getBuildFloor()
    {
        return $this->BuildFloor;
    }

    /**
     * @param mixed $BuildFloor
     */
    public function setBuildFloor($BuildFloor)
    {
        $this->BuildFloor = $BuildFloor;
    }

    /**
     * @return float
     */
    public function getLivingArea()
    {
        return $this->livingArea;
    }

    /**
     * @param float $livingArea
     */
    public function setLivingArea($livingArea)
    {
        $this->livingArea = $livingArea;
    }

    /**
     * @return float
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param float $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return array
     */
    public function getMetro()
    {
        return $this->metro;
    }

    /**
     * @param array $metro
     */
    public function setMetro($metro)
    {
        $this->metro = $metro;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param string $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return Seller
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param Seller $seller
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * @return string
     */
    public function getAddressString()
    {
        return $this->addressString;
    }

    /**
     * @param string $addressString
     */
    public function setAddressString($addressString)
    {
        $this->addressString = $addressString;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
}