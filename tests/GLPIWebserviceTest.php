<?php

class GLPIWebserviceTest extends \PHPUnit\Framework\TestCase
{
    private function getServiceMock()
    {
        $serviceMock = $this->getMockBuilder('VitorBari\GLPIWebservice\Services\Stub')
            ->disableOriginalConstructor()
            ->getMock();
        return $serviceMock;
    }

    public function testGetMyInfo()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue('output'));

        $glpi = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('username', 'password');
//        $output = $glpi->getMyInfo();
//        $this->assertEquals('output', $output);
    }
}