<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Service;

use AdgoalCommon\Base\Domain\Exception\CriticalException;
use AdgoalCommon\Monitoring\StatsdTrait;

/**
 * Class MonitoringService.
 *
 */
class MonitoringService
{
    use StatsdTrait;

    /**
     * @var mixed[]
     */
    private $globalTags = [];

    /**
     * @param string $tagName
     * @param string $tagValue
     */
    public function addGlobalTag(string $tagName, string $tagValue): void
    {
        $this->globalTags[$tagName] = $tagValue;
    }

    public function fireMetric(MetricKeyInterface $metric): void
    {
        $tags = $metric->getTags();
        $tags = array_merge($tags, $this->globalTags); //add global tags
        $tags['human_name'] = $metric->getHumanName();
        switch ($metric->getMetricType()) {
            case MetricKeyInterface::METRIC_TYPE_COUNT:
                $this->statsd->count($metric->getName(), (int) $metric->getValue(), 1.0, $tags);

                break;
            case MetricKeyInterface::METRIC_TYPE_INCREMENT:
                $this->statsd->increment($metric->getName(), (float) $metric->getValue(), $tags);

                break;
            case MetricKeyInterface::METRIC_TYPE_DECREMENT:
                $this->statsd->decrement($metric->getName(), (float) $metric->getValue(), $tags);

                break;
            default:
                throw new CriticalException('Not supported metric type:'.$metric->getMetricType());
        }
    }

    public function flush(): void
    {
        $this->statsd->flushNow();
    }
}
