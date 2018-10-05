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
        exec('df', $output);

        if (!preg_match('~\s+(\d+)% /$~mui', implode("\n", $output), $matches)) {
            throw new RuntimeException('No data');
        }
        $redisWatch = new RedisWatch(new Redis());
        $redisWatch->logValue('disk_free_' . ENV, (int)$matches[1]);
    }
}


