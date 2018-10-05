<?php


class WatchService
{
    public function run()
    {
        $redisWatch = new RedisWatch(new Redis());

        $data = $redisWatch->sliceWatchData();
        foreach ($data as $key => $value) {
            $this->getCloudWatchLogger()->publishEvent($key, $value, 'Count');
        }
    }

    public function getCloudWatchLogger(): CloudWatchLogger
    {

    }
}

