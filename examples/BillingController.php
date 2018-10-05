<?php


class BillingController
{
    public function actionProcess(ChargeRequest $request)
    {
        $billingProcessor = new BillingProcessor($request->getUserId());
        $billingProcessor->process($request->getData());

        return $this->sendResponse();
    }

    public function actionProcessSafe(ChargeRequest $request)
    {
        $redisLock = new RedisLock(new Redis());
        $lockKey = "billing_" . $request->getUserId();

        if (!$redisLock->acquire($lockKey)) {
            throw new RuntimeException("Can't process billing now");
        }

        $billingProcessor = new BillingProcessor($request->getUserId());
        $billingProcessor->process($request->getData());

        $redisLock->release($lockKey);

        return $this->sendResponse();
    }

    private function sendResponse(array $data = [])
    {
    }
}

