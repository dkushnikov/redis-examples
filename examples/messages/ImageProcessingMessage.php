<?php

class ImageProcessingMessage implements IMessage
{

    private $requestId;

    public function __construct(UploadedFile $file)
    {
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getUploadedFile(): UploadedFile
    {
    }
}