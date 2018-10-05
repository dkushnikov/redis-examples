<?php

class ImageProcessingService extends AbstractProcessingService
{
    public const QUEUE_NAME = 'imageProcessing';

    public function run()
    {
        $queue = new RedisQueue(new Redis());
        while ($this->serviceSafePoint()) {

            /** @var ImageProcessingMessage $message */
            $message = $queue->receive(self::QUEUE_NAME);
            if ($message !== null) {
                (new ImageProcessor())->process($message->getUploadedFile());
            } else {
                usleep(100 * 1000);
            }
        }
    }
}

