<?php namespace VitorBari\GLPIWebservice\Services;

class Stub implements ServiceInterface
{
    /**
     * @param array $args
     * @return mixed
     */
    public function call(array $args)
    {
        $json = '{"response": 1}';
        return $this->format($json);
    }

    /**
     * @param string $result
     * @return mixed
     */
    private function format($result)
    {
        return json_decode($result, true);
    }
}
