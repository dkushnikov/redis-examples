<?php

interface CloudWatchLogger
{
    public function publishEvent(string $metricTitle, int $value, string $unit);
}