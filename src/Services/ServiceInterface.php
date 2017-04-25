<?php namespace VitorBari\GLPIWebservice\Services;

interface ServiceInterface
{
    /**
     * @param $login_name
     * @param $login_password
     * @param $username
     * @param $password
     * @return mixed
     */
    public function auth($login_name, $login_password, $username = null, $password = null);

    /**
     * @param array $args
     * @return mixed
     */
    public function call(array $args);
}
