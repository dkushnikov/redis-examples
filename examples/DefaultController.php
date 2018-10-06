<?php

class DefaultController
{
    public function actionUpdate(UpdateRequest $request)
    {
        $user = new User($request->getUserId());
        $user->updateData($request->getData());

        $queue = new RedisInertialQueue(new Redis());
        $queue->send('userAnalytics', new UserAnalyticsMessage($request->getUserId()), 10);
    }

    public function actionProcess()
    {
        $redisWatch = new RedisWatch(new Redis());

        $redisWatch->startTimer('process_time');
        // ... some processing
        $redisWatch->logTimer('process_time');
        $redisWatch->logTick('process_count');
    }

    public function actionMonitorDisk()
    {
        $redisWatch = new RedisWatch(new Redis());

        $rootDir = '/';
        $diskFreeSpace = disk_free_space($rootDir);
        $diskTotalSpace = disk_total_space($rootDir);
        $free = 100 - (int)ceil(100 * $diskFreeSpace / $diskTotalSpace);
        $redisWatch->logValue('disk_free_' . ENV, $free);
    }
}


