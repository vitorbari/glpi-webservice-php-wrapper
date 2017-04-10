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

    public function testTest()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->test();
        $this->assertEquals('output', $output);
    }

    public function testStatus()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->status();
        $this->assertEquals('output', $output);
    }

    public function testListAllMethods()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->listAllMethods();
        $this->assertEquals('output', $output);
    }

    public function testListEntities()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->listEntities();
        $this->assertEquals('output', $output);
    }

    public function testCountEntities()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->will($this->returnValue(array('count' => 10)));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->countEntities();
        $this->assertEquals(10, $output);
    }
}