<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 26/03/14
 * Time: 18.35
 */

namespace MatryoshkaTest\Model\Mock;


class MockDataGataway {

    public function getResultSetPrototype()
    {
        return new \Matryoshka\Model\ResultSet\ResultSet();
    }
} 