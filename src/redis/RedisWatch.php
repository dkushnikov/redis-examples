<?php

class RedisWatch extends AbstarctRedisService
{
    private $timers = [];

    public function startTimer($key): void
    {
        $this->timers[$key] = microtime(true);
    }

    public function logTimer(string $key): int
    {
        $processTime = round((microtime(true) - $this->timers[$key]) * 1000);
        $this->logVolume($key, $processTime);
    }

    public function logTick(string $key): int
    {
        // redis> INCR key
        return $this->redis->incr($key);
    }

    public function logHappened(string $key): int
    {
        // redis> SET key value
        return $this->redis->set($key, 1);
    }

    public function logValue(string $key, int $value)
    {
        // redis> SET key value
        return $this->redis->set($key, $value);
    }

    public function logVolume(string $key, int $volume): int
    {
        // redis> INCRBY key value
        return $this->redis->incrBy($key, $volume);
    }

    public function sliceWatchData(): array
    {
        $data = [];
        foreach ($this->getWatchKeys() as $watchKeyRaw) {
            $data[$watchKeyRaw] = (int)$this->redis->getSet($watchKeyRaw, 0);
        }
        return $data;
    }

    protected function getWatchKeys()
    {
        // redis> KEYS *
        return $this->redis->keys('*');
    }
}




