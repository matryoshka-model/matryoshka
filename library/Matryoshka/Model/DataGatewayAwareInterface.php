<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

interface DataGatewayAwareInterface
{

    /**
     * @param mixed $dataGateway
     */
    public function setDataGateway($dataGateway);

    /**
     * @return mixed
     */
    public function getDataGateway();
}