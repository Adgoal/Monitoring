<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Client;

use Domnikl\Statsd\Client;

/**
 * Class Domnikl.
 */
class Domnikl implements ClientInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Statsd constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->startBatch();
    }

    /**
     * Increments stat counters.
     *
     * @param string    $key
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function increment(string $key, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->increment($key, $sampleRate, $tags);
    }

    /**
     * Decrement stat counters.
     *
     * @param string    $key
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function decrement(string $key, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->decrement($key, $sampleRate, $tags);
    }

    /**
     * Log timing information.
     *
     * @param string    $key
     * @param float     $value      the timing in ms
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function timing(string $key, float $value, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->timing($key, $value, $sampleRate, $tags);
    }

    /**
     * Counting stat.
     *
     * @param string    $key
     * @param int|float $value
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function count(string $key, $value, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->count($key, $value, $sampleRate, $tags);
    }

    /**
     * Gauge stat.
     *
     * @param string   $key
     * @param int      $value
     * @param string[] $tags
     */
    public function gauge(string $key, int $value, array $tags = []): void
    {
        $this->client->gauge($key, $value, $tags);
    }

    /**
     * report memory usage to statsd. if memory was not given report peak usage.
     *
     * @param string    $key
     * @param int|null  $memory
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function memory(string $key, ?int $memory = null, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->memory($key, $memory, $sampleRate, $tags);
    }

    /**
     * Flush all metric messages immediately.
     */
    public function flush(): void
    {
        $this->client->endBatch();
        $this->client->startBatch();
    }
}
