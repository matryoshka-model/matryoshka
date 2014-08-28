<?php

namespace MatryoshkaTest\Model\Mock;


class MockDataGataway {

    public function getResultSetPrototype()
    {
        return new \Matryoshka\Model\ResultSet\ResultSet();
    }
} 