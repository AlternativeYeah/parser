<?php
/**
 * Created by PhpStorm.
 * User: igorvolkov
 * Date: 18.12.2017
 * Time: 23:09
 */

namespace AppBundle\Service\Filter;


use AppBundle\Model\BaseModel;

abstract class Filter
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $sort;

    /**
     * @param mixed $data
     * @return mixed
     */
    public function filter($data)
    {
        if (is_array($data)) {
            $items = array();
            foreach ($data as $item) {
                if ($this->filtration($item)) {
                    $items[] = $item;
                }

                if($this->getLimit() !== null){
                    if((count($items)) == $this->getLimit()){
                        return $items;
                    }
                }
            }
            return $items;
        }else{
            return $this->filtration($data);
        }
    }

    abstract protected function filtration(BaseModel $baseModel);

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }
}