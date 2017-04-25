<?php

use VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException;

class GLPIWebserviceTest extends \PHPUnit\Framework\TestCase
{
    private function getServiceMock()
    {
        $serviceMock = $this->getMockBuilder('VitorBari\GLPIWebservice\Services\ServiceInterface')
            ->getMock();
        return $serviceMock;
    }

    public function testGetSession()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));

        $glpi = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('abc', '1234');

        $output = $glpi->getSession();
        $this->assertEquals(['name' => 'abc'], $output);
    }

    public function testNotAuthenticatedException()
    {
        $serviceMock = $this->getServiceMock();
        $glpi        = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('abc', '1234');

        $this->expectException(NotAuthenticatedException::class);
        $glpi->getSession();
    }

    public function testAuth()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->with($this->equalTo('abc'), $this->equalTo('1234'))
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->auth('abc', '1234');

        $this->assertEquals($glpi, $output);
    }

    public function testAuthWsUser()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->with(
                $this->equalTo('abc'),
                $this->equalTo('1234'),
                $this->equalTo('user'),
                $this->equalTo('password')
            )
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->auth('abc', '1234', 'user', 'password');

        $this->assertEquals($glpi, $output);
    }

    public function testLogout()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.doLogout', 'session' => '1234'])
            ->will($this->returnValue('output'));

        $glpi = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('abc', '1234');

        $output = $glpi->logout();
        $this->assertEquals('output', $output);
    }

    public function testGetMyInfo()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.getMyInfo', 'session' => '1234'])
            ->will($this->returnValue('output'));

        $glpi = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('abc', '1234');

        $output = $glpi->getMyInfo();
        $this->assertEquals('output', $output);
    }

    public function testGetMyInfoId2Name()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('auth')
            ->will($this->returnValue(['name' => 'abc', 'session' => '1234']));
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.getMyInfo', 'session' => '1234', 'id2name' => true])
            ->will($this->returnValue('output'));

        $glpi = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $glpi->auth('abc', '1234');

        $output = $glpi->getMyInfo(true);
        $this->assertEquals('output', $output);
    }

    public function testTest()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.test'])
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->test();
        $this->assertEquals('output', $output);
    }

    public function testStatus()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.status'])
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->status();
        $this->assertEquals('output', $output);
    }

    public function testListAllMethods()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.listAllMethods'])
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->listAllMethods();
        $this->assertEquals('output', $output);
    }

    public function testListEntities()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.listEntities'])
            ->will($this->returnValue('output'));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->listEntities();
        $this->assertEquals('output', $output);
    }

    public function testCountEntities()
    {
        $serviceMock = $this->getServiceMock();
        $serviceMock->expects($this->once())->method('call')
            ->with(['method' => 'glpi.listEntities', 'count' => true])
            ->will($this->returnValue(array('count' => 10)));

        $glpi   = new VitorBari\GLPIWebservice\GLPIWebservice($serviceMock);
        $output = $glpi->countEntities();
        $this->assertEquals(10, $output);
    }
}
