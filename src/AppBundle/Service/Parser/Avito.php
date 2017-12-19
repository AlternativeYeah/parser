<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 15.12.2017
 * Time: 20:12
 */

namespace AppBundle\Service\Parser;

use AppBundle\Model\Apartment;
use AppBundle\Model\Seller;
use PHPHtmlParser\Dom;

class Avito implements Parser
{
    const URL = 'https://www.avito.ru';

    const CITY_MOSCOW = 'moskva';
    const CITY_KAZAN = 'kazan';

    const AD_TYPE_SALE = 'prodam';
    const AD_TYPE_LEASE = 'sdam';

    const BUILD_TYPE_APARTMENT = 'kvartiry';

    public $paramsList = [
        'floor' => 'Этаж',
        'roomsCount' => 'Количество комнат',
        'buildFloor' => 'Этажей в доме',
        'area' => 'Общая площадь',
        'livingArea' => 'Жилая площадь',
    ];

    /**
     * @param $city
     * @param $adType
     * @param $buildType
     * @param $limit
     * @return array
     */
    public function parse($city, $adType, $buildType, $limit)
    {
        $data = array();
        $page = 1;
        while ($limit > count($data)) {
            $dom = new Dom();
            $url = $this->generateUrl($city, $adType, $buildType, $page);
            sleep(6);
            $dom->loadFromUrl($url);
            $items = $dom->find('.item');
            /** @var Dom\HtmlNode $apartment */
            foreach ($items as $item) {
                $href = $item->find('.description', 0)
                    ->find('.title', 0)
                    ->find('a', 0)
                    ->href;
                $link = self::URL . $href;
                $apartmentPage = new Dom();

                sleep(6);
                $apartmentPage->loadFromUrl($link);

                $apartment = new Apartment();
                $apartment->setLink($link);
                $apartment->setId($this->parseId($item));
                $apartment->setPrice($this->parsePrice($apartmentPage));
                $apartment->setDescription($this->parseDescription($apartmentPage));
                $apartment->setSeller($this->parseSeller($apartmentPage));
                $apartment->setMetro($this->parseMetro($apartmentPage));
                $apartment->setAddressString($this->parseAddress($apartmentPage));
                $apartment->setAdType($this->parseAdType($apartmentPage));

                //Вытаскиваем пачку параметров из одного блока и берем только нужные
                $apartmentParams = $apartmentPage->find('.item-params-list-item');
                foreach ($apartmentParams as $param) {
                    $paramKey = $this->getKey($param);
                    $key = array_search($paramKey, $this->paramsList);
                    if (false !== $key) {
                        $parse = 'parse' . ucfirst($key);
                        $set = 'set' . ucfirst($key);
                        $apartment->$set($this->$parse($param));
                    }
                }
                $data[] = $apartment;
            }
            $page++;
        }
        return $data;
    }

    /**
     * @param $city
     * @param $adType
     * @param $buildType
     * @param $page
     * @return string
     */
    protected function generateUrl($city, $adType, $buildType, $page)
    {
        $url = self::URL . '/' . $city . '/' . $buildType . '/' . $adType;
        $url = $page > 1 ? $url . '?p=' . $page : $url;
        return $url;
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return string
     */
    protected function parseId(Dom\HtmlNode $dom)
    {
        return $dom->id;
    }

    /**
     * @param Dom $dom
     * @return string
     */
    protected function parsePrice(Dom $dom)
    {
        $price = $dom->find('.price-value-string', 0)->text;
        preg_match_all('/\d+/', $price, $matches);
        $price = implode('', array_shift($matches));
        return $price;
    }

    /**
     * @param Dom $dom
     * @return null|string
     */
    protected function parseDescription(Dom $dom)
    {
        $dom = $dom->find('.item-description-text', 0);
        if ($dom) {
            return $dom->find('p', 0)->text;
        }
        return null;
    }

    /**
     * @param Dom $dom
     * @return string
     */
    protected function parseAddress(Dom $dom)
    {
        $street = $dom->find('.item-map-address', 0)->getChildren()[0]->getChildren()[0]->text;
        $city = $dom->find('.item-map-location', 0)->getChildren()[3]->text;
        $address = $city . $street;
        return $address;
    }

    /**
     * @param Dom $dom
     * @return array
     */
    protected function parseMetro(Dom $dom)
    {
        $metro = array();
        $dom->find('.item-map-metro');
        foreach ($dom as $station) {
            $metro[] = $station->text;
        }
        return $metro;
    }

    /**
     * @param Dom $dom
     * @return Seller
     */
    protected function parseSeller(Dom $dom)
    {
        $seller = new Seller();
        $name = trim($dom->find('.seller-info-name', 0)->find('a', 0)->text);
        $type = trim($dom->find('.seller-info-name', 0)->getParent()->getParent()->text);
        switch ($type) {
            case 'Продавец':
                $seller->setType(Seller::SELLER_TYPE_OWNER);
                break;
            case 'Агентство':
                $seller->setType(Seller::SELLER_TYPE_AGENT);
                break;
        }

        $seller->setName($name);
        return $seller;
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return int
     */
    protected function parseFloor(Dom\HtmlNode $dom)
    {
        return intval($dom->text);
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return int
     */
    protected function parseBuildFloor(Dom\HtmlNode $dom)
    {
        return intval($dom->text);
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return int
     */
    protected function parseRoomsCount(Dom\HtmlNode $dom)
    {
        return intval($dom->text);
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return mixed|string
     */
    protected function parseArea(Dom\HtmlNode $dom)
    {
        $area = $dom->text;
        preg_match_all('/\d+/', $area, $matches);
        $area = array_shift($matches);
        return $area;
    }

    /**
     * @param Dom\HtmlNode $dom
     * @return int
     */
    protected function parseLivingArea(Dom\HtmlNode $dom)
    {
        return intval($dom->text);
    }

    /**
     * @param Dom $dom
     * @return string
     */
    protected function parseAdType(Dom $dom)
    {
        $adType = $dom->find('.breadcrumbs', 0)->find('a', 3)->text;

        switch ($adType) {
            case 'Продам':
                return Apartment::AD_TYPE_SALE;
                break;
            case 'Сдам':
                return Apartment::AD_TYPE_LEASE;
                break;
        }
    }

    /**
     * @param $item
     * @return string
     */
    protected function getKey($item)
    {
        $label = $item->find('.item-params-label', 0);
        $key = trim(preg_replace('/:/', '', $label->text));
        return $key;
    }
}