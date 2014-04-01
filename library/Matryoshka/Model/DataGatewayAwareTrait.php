<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

/**
 * Trait DataGatewayAwareTrait
 */
trait DataGatewayAwareTrait
{

    /**
     * Data Gateway
     * @var mixed
     */
    protected $dataGateway;

    /**
     * Set Data Gateway
     * @param mixed $dataGateway
     * @return $this
     */
    public function setDataGateway($dataGateway)
    {
        $this->dataGateway = $dataGateway;
        return $this->dataGateway;
    }

    /**
     * Get Data Gateway
     * @return mixed
     */
    public function getDataGateway()
    {
        return $this->dataGateway;
    }
}
