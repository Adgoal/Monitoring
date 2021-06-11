<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Client;

use Liuggio\StatsdClient\Service\StatsdService;

/**
 * Class Liuggio.
 */
class Liuggio implements ClientInterface
{
    /**
     * @var StatsdService
     */
    private $client;

    /**
     * Statsd constructor.
     *
     * @param StatsdService $client
     */
    public function __construct(StatsdService $client)
    {
        $this->client = $client;
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
        $this->client->setSamplingRate($sampleRate);
        $this->client->increment($key);
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
        $this->client->setSamplingRate($sampleRate);
        $this->client->decrement($key);
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
        $this->client->setSamplingRate($sampleRate);
        $this->client->timing($key, $value);
    }

    /**
     * Counting stat.
     *
     * @param string    $key
     * @param float|int $value
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function count(string $key, $value, $sampleRate = 1.0, array $tags = []): void
    {
        $this->client->setSamplingRate($sampleRate);
        $this->client->updateCount($key, $value);
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
        $this->client->setSamplingRate(1.0);
        $this->client->gauge($key, $value);
    }

    /**
     * report memory usage to statsd. if memory was not given report peak usage.
     *
     * @param string    $key
     * @param int       $memory
     * @param float|int $sampleRate
     * @param string[]  $tags
     */
    public function memory(string $key, ?int $memory = null, $sampleRate = 1.0, array $tags = []): void
    {
        if (null === $memory) {
            $memory = memory_get_peak_usage();
        }

        $this->count($key, $memory, $sampleRate, $tags);
    }

    /**
     * Flush all metric messages immediately.
     */
    public function flush(): void
    {
        $this->client->flush();
    }
}
