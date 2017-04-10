<?php namespace VitorBari\GLPIWebservice\Services;

interface ServiceInterface
{
    /**
     * @param array $args
     * @return mixed
     */
    public function call(array $args);
}
