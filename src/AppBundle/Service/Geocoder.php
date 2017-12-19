<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 18.12.2017
 * Time: 0:48
 */

namespace AppBundle\Service;

use AppBundle\Model\Address;
use AppBundle\Model\Apartment;
use GuzzleHttp\Client;

class Geocoder
{
    const REQUEST_FORMAT_JSON = 'json';

    const BASE_URI = 'https://geocode-maps.yandex.ru/';

    protected $version = '1.x';

    protected $client;

    private $city;

    private $street;

    private $house;

    private $position;

    public function __construct()
    {
        $this->client = new Client(
            array('base_uri' => self::BASE_URI)
        );
    }

    public function getGeo(Apartment $apartment)
    {
        $queryData = array(
            'format' => self::REQUEST_FORMAT_JSON,
            'geocode' => $apartment->getAddressString(),
        );
        $response = $this->client->request(
            'GET',
            $this->version,
            array('query' => $queryData)
        );
        $data = \GuzzleHttp\json_decode($response->getBody()->getContents());
        if (isset($data->response)) {
            $this->parseResponse($data);
            $address = new Address();
            $address->setPosition($this->getPosition($data));
            $address->setCity($this->getCity($data));
            $address->setStreet($this->getStreet($data));
            $address->setHouse($this->getHouse($data));
            $apartment->setAddress($address);
            return $apartment;
        }
    }

    protected function parseResponse($data)
    {
        $featureMember = array_shift($data
            ->response
            ->GeoObjectCollection
            ->featureMember);
        if ($featureMember) {
            $geoObject = $featureMember->GeoObject;
            $this->setPosition($geoObject->Point->pos);

            $administrativeArea = $geoObject
                ->metaDataProperty
                ->GeocoderMetaData
                ->AddressDetails
                ->Country
                ->AdministrativeArea;
            $this->setCity($administrativeArea->AdministrativeAreaName ?? null);

            if (isset($administrativeArea->SubAdministrativeArea)) {
                $administrativeArea = $administrativeArea->SubAdministrativeArea;
            }

            if (isset($administrativeArea->Locality)) {
                $locality = $administrativeArea->Locality;

                if (isset($locality->Thoroughfare)) {
                    $thoroughfare = $locality->Thoroughfare;
                    $this->setStreet($thoroughfare->ThoroughfareName ?? null);

                    if (isset($thoroughfare->Premise)) {
                        $premise = $thoroughfare->Premise;
                        $this->setHouse($premise->PremiseNumber ?? null);
                    }
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return mixed
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @param mixed $house
     */
    public function setHouse($house)
    {
        $this->house = $house;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}