<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring;

use AdgoalCommon\Monitoring\Client\ClientInterface;

/**
 * Class Statsd.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Statsd
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Metric name prefix.
     *
     * @var string
     */
    private $metricPrefix;

    /**
     * Amount of metric messages, that was set.
     *
     * @var int
     */
    private $amountMetricMessages = 0;

    /**
     * Amount of metric messaged, that should be send in one package.
     *
     * @var int
     */
    private $batchMessagesSize;

    /**
     * @var mixed[]
     */
    private $globalTags = [];

    /**
     * Statsd constructor.
     *
     * @param ClientInterface $client
     * @param string          $metricPrefix
     * @param int             $batchMessagesSize
     */
    public function __construct(ClientInterface $client, string $metricPrefix, int $batchMessagesSize)
    {
        $this->client = $client;
        $this->metricPrefix = $metricPrefix;
        $this->batchMessagesSize = $batchMessagesSize;
    }

    /**
     * @param string $tagName
     * @param string $tagValue
     */
    public function addGlobalTag(string $tagName, string $tagValue): void
    {
        $this->globalTags[$tagName] = $tagValue;
    }

    /**
     * @param string $tagName
     */
    public function removeGlobalTag(string $tagName): void
    {
        unset($this->globalTags[$tagName]);
    }

    /**
     * increments the key by 1.
     *
     * @param string   $eventName
     * @param float    $sampleRate
     * @param string[] $tags
     */
    public function increment(string $eventName, float $sampleRate = 1.0, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->increment($metricName, $sampleRate, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * Decrement stat counters.
     *
     * @param string   $eventName
     * @param float    $sampleRate
     * @param string[] $tags
     */
    public function decrement(string $eventName, float $sampleRate = 1.0, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->decrement($metricName, $sampleRate, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * Log timing information.
     *
     * @param string   $eventName
     * @param float    $time       the timing in ms
     * @param float    $sampleRate
     * @param string[] $tags
     */
    public function timing(string $eventName, float $time, float $sampleRate = 1.0, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->timing($metricName, $time, $sampleRate, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * Counting stat.
     *
     * @param string    $eventName
     * @param int|float $count
     * @param float     $sampleRate
     * @param string[]  $tags
     */
    public function count(string $eventName, $count, float $sampleRate = 1.0, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->count($metricName, $count, $sampleRate, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * Gauge stat.
     *
     * @param string   $eventName
     * @param int      $value
     * @param string[] $tags
     */
    public function gauge(string $eventName, int $value, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->gauge($metricName, $value, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * report memory usage to statsd. if memory was not given report peak usage.
     *
     * @param string   $eventName
     * @param int|null $memory
     * @param float    $sampleRate
     * @param string[] $tags
     */
    public function memory(string $eventName, ?int $memory = null, float $sampleRate = 1.0, array $tags = []): void
    {
        $metricName = $this->makeMetricName($eventName);
        $this->client->memory($metricName, $memory, $sampleRate, array_merge($tags, $this->globalTags));
        $this->flush();
    }

    /**
     * Flush all metric messages immediately.
     */
    public function flushNow(): void
    {
        if ($this->amountMetricMessages > 0) {
            $this->amountMetricMessages = 0;
            $this->client->flush();
        }
    }

    /**
     * Make and return stat metric name.
     *
     * @param string $name
     *
     * @return string
     */
    private function makeMetricName(string $name): string
    {
        return $this->metricPrefix.'.'.$name;
    }

    /**
     * Send all buffered data to statsd.
     */
    private function flush(): void
    {
        ++$this->amountMetricMessages;

        if ($this->amountMetricMessages >= $this->batchMessagesSize) {
            $this->amountMetricMessages = 0;
            $this->client->flush();
        }
    }

    /**
     * Send all statistic to statsd.
     */
    public function __destruct()
    {
        if ($this->amountMetricMessages > 0) {
            $this->client->flush();
        }
    }
}
