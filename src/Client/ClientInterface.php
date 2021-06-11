<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Client;

interface ClientInterface
{
    /**
     * increments the key by 1.
     *
     * @param string    $key
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function increment(string $key, $sampleRate = 1.0, array $tags = []): void;

    /**
     * decrements the key by 1.
     *
     * @param string    $key
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function decrement(string $key, $sampleRate = 1.0, array $tags = []): void;

    /**
     * sends a count to statsd.
     *
     * @param string    $key
     * @param int|float $value
     * @param float     $sampleRate
     * @param string[]  $tags
     */
    public function count(string $key, $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * sends a timing to statsd (in ms).
     *
     * @param string    $key
     * @param float     $value      the timing in ms
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function timing(string $key, float $value, $sampleRate = 1.0, array $tags = []): void;

    /**
     * report memory usage to statsd. if memory was not given report peak usage.
     *
     * @param string    $key
     * @param int       $memory
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function memory(string $key, ?int $memory = null, $sampleRate = 1.0, array $tags = []): void;

    /**
     * sends a gauge, an arbitrary value to StatsD.
     *
     * @param string   $key
     * @param int      $value
     * @param string[] $tags
     */
    public function gauge(string $key, int $value, array $tags = []): void;

    /**
     * Flush all metric messages immediately.
     */
    public function flush(): void;
}
