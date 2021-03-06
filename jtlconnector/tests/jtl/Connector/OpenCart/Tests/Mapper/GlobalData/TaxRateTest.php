<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\TaxRate;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class TaxRateTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new TaxRate();
    }

    protected function getEndpoint()
    {
        return [
            'tax_rate_id' => '1',
            'rate' => 12.43
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\TaxRate();
        $result->setId(new Identity("1", 0));
        $result->setRate(12.43);
        return $result;
    }

    public function testPush()
    {
    }
}
