<?php

interface UpdateRequest
{
    public function getUserId(): int;

    public function getData(): array;
}

