<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\TestAsset\ArrayGateway;

/**
 * Simple Array datagateway for mock and testing
 *
 */
class ArrayGateway
{
    protected $data = [];

    protected function testCriteria(array $criteria, array $element)
    {
        foreach ($criteria as $k => $v) {
            if (!isset($element[$k]) || $element[$k] !== $v) {
                return false;
            }
        }

        return true;
    }

    public function find(array $criteria)
    {
        $return = [];
        foreach ($this->data as $element) {
            if ($this->testCriteria($criteria, $element)) {
                $return[] = $element;
            }
        }

        return $return;
    }

    public function insert(array $element)
    {
        $this->data[] = $element;
        return $this;
    }

    public function update(array $criteria, array $newElement)
    {
        foreach ($this->data as $k => $element) {
            if ($this->testCriteria($criteria, $element)) {
                $this->data[$k] = $newElement;
                return true;
            }
        }

        return false;
    }

    public function delete(array $criteria)
    {
        foreach ($this->data as $k => $element) {
            if ($this->testCriteria($criteria, $element)) {
                unset($this->data[$k]);
                return true;
            }
        }

        return false;
    }
}