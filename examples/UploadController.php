<?php
define('ENV', 'prod');

class UploadController
{
    public function actionUpload(UploadRequest $request)
    {
        $imageProcessor = new ImageProcessor();

        $result = $imageProcessor->process($request->getUploadedFile());

        return $this->sendResponse($result);
    }

    public function actionUploadAsync(UploadRequest $request)
    {
        $queue = new RedisQueue(new Redis());

        $queueMessage = new ImageProcessingMessage($request->getUploadedFile());
        $queue->send(ImageProcessingService::QUEUE_NAME, $queueMessage);

        return $this->sendResponse();
    }

    private function sendResponse(array $data = [])
    {
    }
}





