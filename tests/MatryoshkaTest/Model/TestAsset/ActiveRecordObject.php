<?php
namespace MatryoshkaTest\Model\TestAsset;

use Matryoshka\Model\Object\AbstractActiveRecord;

class ActiveRecordObject extends AbstractActiveRecord
{

    public $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}