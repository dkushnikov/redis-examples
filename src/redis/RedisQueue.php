<?php

class RedisQueue extends AbstarctRedisService
{
    public function send(string $queueName, IMessage $message)
    {
        // redis> RPUSH key value
        return $this->redis->rPush($queueName, $this->serialize($message));
    }

    public function receive(string $queueName): ?IMessage
    {
        // redis> LPOP key
        $queueMessage = $this->redis->lPop($queueName);
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

