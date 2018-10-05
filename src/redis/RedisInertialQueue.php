<?php


class RedisInertialQueue extends AbstarctRedisService
{
    public function send(string $queueName, IMessage $message, int $timeout = 0)
    {
        $arguements = [$queueName, time() + $timeout, $this->serialize($message)];
        // redis> ZADD key score member
        return (boolean)$this->redis->rawCommand('ZADD', $arguements);
    }

    public function receive(string $queueName): ?IMessage
    {
        $queueMessage = $this->redis->eval("
local val = redis.call('ZRANGEBYSCORE', KEYS[1], 0, ARGV[1], 'LIMIT', 0, 1)[1]
if val then
    redis.call('ZREM', KEYS[1], val)
end
return val
            ", [$queueName, time()], 1);

        if (false === $queueMessage) {
            return null;
        }
        return $this->unserialize($queueMessage);
    }

    protected function serialize(IMessage $message): string
    {
        // serialize
    }

    protected function unserialize(string $value): IMessage
    {
        // unserialize
    }
}

