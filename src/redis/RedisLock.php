<?php

class RedisLock extends AbstarctRedisService
{
    private $locks = [];
    private $lockValues = [];

    public function acquire(string $name, int $timeout = 0, int $expire = 30): bool
    {
        if ($this->acquireLock($name, $timeout, $expire)) {
            $this->locks[] = $name;
            return true;
        }
        return false;
    }

    protected function acquireLock(string $name, int $timeout, int $expire): bool
    {
        $value = md5($name . random_int(0, PHP_INT_MAX));
        $waitTime = 0;
        $arguments = [$name, $value, 'NX', 'PX', $expire * 1000];
        while (!$this->redis->rawCommand('SET', ...$arguments)) {
            $waitTime++;
            if ($waitTime > $timeout) {
                return false;
            }
            sleep(1);
        }
        $this->lockValues[$name] = $value;
        return true;
    }

    public function release(string $name): bool
    {
        if ($this->releaseLock($name)) {
            $index = array_search($name, $this->locks, true);
            if ($index !== false) {
                unset($this->locks[$index]);
            }
            return true;
        }
        return false;
    }

    protected function releaseLock(string $name): bool
    {
        $releaseScript = '
            if redis.call("GET",KEYS[1])==ARGV[1] then
                return redis.call("DEL",KEYS[1])
            else
                return 0
            end
        ';
        if (!isset($this->lockValues[$name])) {
            return false;
        }
        $arguments = [$releaseScript, 1, $name, $this->lockValues[$name]];
        if (!$this->redis->rawCommand('EVAL', ...$arguments)) {
            return false;
        }
        unset($this->lockValues[$name]);

        return true;
    }

}



