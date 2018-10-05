<?php


class AbstarctRedisService
{
    /** @var Redis */
    protected $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }


}