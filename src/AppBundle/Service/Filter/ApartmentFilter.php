<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 18.12.2017
 * Time: 23:09
 */

namespace AppBundle\Service\Filter;

use AppBundle\Model\Apartment;
use AppBundle\Model\BaseModel;

class ApartmentFilter extends Filter
{
    /**
     * @var array
     */
    private $adType;

    /**
     * @var array
     */
    private $city;

    /**
     * @var array
     */
    private $sellerType;

    /**
     * @var int
     */
    private $floorMax;

    /**
     * @var int
     */
    private $floorMin;

    /**
     * @var float
     */
    private $priceMax;

    /**
     * @var float
     */
    private $priceMin;

    protected function filtration(BaseModel $apartment){
        /** @var Apartment $apartment */
        if($this->adType){
            if(!in_array($apartment->getAdType(), $this->adType)){
                return false;
            }
        }
        if($this->city){
            if(!in_array($apartment->getAddress()->getCity(), $this->city)){
                return false;
            }
        }
        if($this->sellerType){
            if(!in_array($apartment->getSeller()->getType(), $this->sellerType)){
                return false;
            }
        }
        if($this->floorMax !== null){
            if($this->floorMax < $apartment->getFloor()){
                return false;
            }
        }

        if($this->floorMin !== null){
            if($this->floorMin > $apartment->getFloor()){
                return false;
            }
        }
        if($this->priceMax !== null){
            if($this->priceMax < $apartment->getPrice()){
                return false;
            }
        }

        if($this->priceMin !== null){
            if($this->priceMin > $apartment->getPrice()){
                return false;
            }
        }
        return $apartment;
    }

    /**
     * @return array
     */
    public function getAdType(): array
    {
        return $this->adType;
    }

    /**
     * @param array|string $adType
     */
    public function setAdType($adType)
    {
        $this->adType = $adType;
    }

    /**
     * @return array
     */
    public function getCity(): array
    {
        return $this->city;
    }

    /**
     * @param array $city
     */
    public function setCity(array $city)
    {
        $this->city = $city;
    }

    /**
     * @return array
     */
    public function getSellerType(): array
    {
        return $this->sellerType;
    }

    /**
     * @param array $sellerType
     */
    public function setSellerType(array $sellerType)
    {
        $this->sellerType = $sellerType;
    }

    /**
     * @return int
     */
    public function getFloorMax(): int
    {
        return $this->floorMax;
    }

    /**
     * @param int $floorMax
     */
    public function setFloorMax(int $floorMax)
    {
        $this->floorMax = $floorMax;
    }

    /**
     * @return int
     */
    public function getFloorMin(): int
    {
        return $this->floorMin;
    }

    /**
     * @param int $floorMin
     */
    public function setFloorMin(int $floorMin)
    {
        $this->floorMin = $floorMin;
    }

    /**
     * @return float
     */
    public function getPriceMax(): float
    {
        return $this->priceMax;
    }

    /**
     * @param float $priceMax
     */
    public function setPriceMax(float $priceMax)
    {
        $this->priceMax = $priceMax;
    }

    /**
     * @return float
     */
    public function getPriceMin(): float
    {
        return $this->priceMin;
    }

    /**
     * @param float $priceMin
     */
    public function setPriceMin(float $priceMin)
    {
        $this->priceMin = $priceMin;
    }
}