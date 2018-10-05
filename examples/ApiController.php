<?php


class ApiController
{
    /** @var User */
    protected $user;

    protected function beforeAction($actionName)
    {
        $redisStorage = new RedisStorage(new Redis());

        $timeout = 1; // seconds
        $max = 10;

        $time = floor(time() / $timeout);
        $actionKey = "api({$actionName})_user({$this->user->getUserId()})_time({$time})";

        $increment = $redisStorage->increx($actionKey, $timeout);

        if ($increment > $max) {
            throw new RuntimeException('Max rps reached');
        }
    }

    public function actionSendMessage()
    {
        $this->beforeAction('sendMessage');

        // ... processing
    }
}

