<?php

class NotAuthenticatedExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testIsException()
    {
        $this->expectException('VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException');
        throw new VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException();
    }

    public function testInstanceOfException()
    {
        try {
            throw new VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException();
        } catch (Exception $ex) {
            $this->assertInstanceOf('VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException', $ex);
            $this->assertInstanceOf('\Exception', $ex);
        }
    }

    public function testExceptionDefaultMessage()
    {
        try {
            throw new VitorBari\GLPIWebservice\Exceptions\NotAuthenticatedException();
        } catch (Exception $ex) {
            $this->assertEquals('You are not authenticated.', $ex->getMessage());
        }
    }
}