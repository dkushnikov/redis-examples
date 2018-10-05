<?php


class RedisStorage extends AbstarctRedisService
{
    public function incr(string $key): int
    {
        return $this->redis->incr($key);
    }

    public function increx(string $key, int $timeout): int
    {
        $increment = $this->redis->incr($key);
        if (1 === $increment) {
            $this->redis->expire($key, $timeout);
        }
        return $increment;
    }

    public function decr(string $key): int
    {
        return $this->redis->decr($key);
    }
}

