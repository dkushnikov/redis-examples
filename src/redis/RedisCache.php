<?php

class RedisCache extends AbstarctRedisService
{
    public function get(string $id)
    {
        // redis> GET key
        $value = $this->redis->get($id);
        if ($value === false) {
            return $value;
        }
        return $this->unserialize($value);
    }

    public function set(string $id, $value, int $expire = 0)
    {
        // redis> SETEX key ttl value
        return $this->redis->setex($id, $expire, $this->serialize($value));
    }

    public function delete(string $id)
    {
        // redis> DEL key
        return $this->redis->delete($id);
    }

    protected function serialize($value): string
    {
        // serialize
    }

    protected function unserialize(string $value)
    {
        // unserialize
    }
}