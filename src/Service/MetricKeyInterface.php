<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Service;

/**
 * Interface MetricKeyInterface.
 *
 */
interface MetricKeyInterface
{
    public const METRIC_TYPE_INCREMENT = 'increment';
    public const METRIC_TYPE_DECREMENT = 'decrement';
    public const METRIC_TYPE_COUNT = 'count';

    public function getMetricType(): string;

    public function getName(): string;

    public function getHumanName(): string;

    public function getDescription(): string;

    public function getValue(): string;

    /**
     * @return string[]
     */
    public function getTags(): array;
}
