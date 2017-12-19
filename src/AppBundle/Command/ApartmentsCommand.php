<?php

namespace AppBundle\Command;

use AppBundle\Model\Apartment;
use AppBundle\Model\Seller;
use AppBundle\Service\Filter\ApartmentFilter;
use AppBundle\Service\Geocoder;
use AppBundle\Service\Parser\Avito;
use AppBundle\Service\Sort\ApartmentSort;
use AppBundle\Service\Sort\SortInterfase;
use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ApartmentsCommand extends ContainerAwareCommand
{
    use ControllerTrait;

    protected function configure()
    {
        $this->setName('apartments');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Avito $serviceParse */
        $serviceParse = $this->getContainer()->get('parse.avito');
        /** @var Geocoder $serviceCoordinates */
        $serviceCoordinates = $this->getContainer()->get('geocoder');
        /** @var ApartmentFilter $serviceFilter */
        $serviceFilter = $this->getContainer()->get('filter.apartment');
        /** @var ApartmentSort $serviceSort */
        $serviceSort = $this->getContainer()->get('sort.apartment');

        $output->writeln('Получаем квартиры на продажу');
        $apartmentsSale = $serviceParse->parse(
            Avito::CITY_MOSCOW,
            Avito::AD_TYPE_SALE,
            Avito::BUILD_TYPE_APARTMENT,
            100
        );

        $output->writeln('Получаем геопозицию');
        /** @var Apartment $apartment */
        foreach ($apartmentsSale as $apartment) {
            $serviceCoordinates->getGeo($apartment);
        }

        $output->writeln('Получаем квартиры в аренду');
        $apartmentsLease = $serviceParse->parse(
            Avito::CITY_MOSCOW,
            Avito::AD_TYPE_LEASE,
            Avito::BUILD_TYPE_APARTMENT,
            1
        );

        $output->writeln('Получаем геопозицию');
        /** @var Apartment $apartment */
        foreach ($apartmentsLease as $apartment) {
            $serviceCoordinates->getGeo($apartment);
        }

        $serviceFilter->setAdType([Apartment::AD_TYPE_SALE]);
        $serviceFilter->setCity(['Москва']);
        $serviceFilter->setSellerType([Seller::SELLER_TYPE_OWNER]);
        $serviceFilter->setFloorMax(7);
        $serviceFilter->setFloorMin(2);
        $serviceFilter->setPriceMax(10000000);
        $serviceFilter->setLimit(5);

        $apartments = $serviceFilter->filter($apartmentsSale);

        $apartments = $serviceSort->sortByPrice($apartments, SortInterfase::DESC);

        $data = array();
        foreach ($apartments as $apartment){
            $data[] = $this->transform($apartment);
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $fs = new Filesystem();
        $fs->dumpFile('web/apartment.json', $json);
        $output->writeln('json файл доступен в web/apartment.json');
    }

    public function transform(Apartment $apartment)
    {
        return [
            'id' => $apartment->getId(),
            'link' => $apartment->getLink(),
            'adType' => $apartment->getAdType(),
            'buildType' => $apartment->getBuildType(),
            'roomsCount' => $apartment->getRoomsCount(),
            'floor' => $apartment->getFloor(),
            'buildFloor' => $apartment->getBuildFloor(),
            'livingArea' => $apartment->getLivingArea(),
            'area' => $apartment->getArea(),
            'metro' => $apartment->getMetro(),
            'price' => $apartment->getPrice(),
            'description' => $apartment->getDescription(),
            'seller' => [
                'name' => $apartment->getSeller()->getName(),
                'type' => $apartment->getSeller()->getType(),
            ],
            'address' => [
                'city' => $apartment->getAddress()->getCity(),
                'street' => $apartment->getAddress()->getStreet(),
                'house' => $apartment->getAddress()->getHouse(),
                'position' => $apartment->getAddress()->getPosition(),
            ]
        ];

    }
}
