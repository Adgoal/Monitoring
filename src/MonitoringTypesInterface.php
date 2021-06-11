<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring;

/**
 * Class MonitoringTypesInterface.
 */
interface MonitoringTypesInterface
{
    public const MONITORING_TYPE_STARTED = 'started';
    public const MONITORING_TYPE_SUCCEEDED = 'succeeded';
    public const MONITORING_TYPE_FAILED = 'failed';
    public const MONITORING_TYPE_ADDED = 'added';
    public const MONITORING_TYPE_UPDATED = 'updated';
    public const MONITORING_TYPE_DELETED = 'deleted';
    public const MONITORING_TYPE_REQUESTED = 'requested';
    public const MONITORING_TYPE_RECEIVED = 'count.received';
}
