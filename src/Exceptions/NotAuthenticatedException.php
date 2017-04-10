<?php namespace VitorBari\GLPIWebservice\Exceptions;

class NotAuthenticatedException extends \Exception
{
    protected $message = 'You are not authenticated.';
}
