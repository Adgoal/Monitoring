<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring;

/**
 * Trait StatsdTrait.
 */
trait StatsdTrait
{
    /**
     * @var Statsd
     */
    private $statsd;

    /**
     * @required
     *
     * @param Statsd $statsd
     */
    public function setStatsdClient(Statsd $statsd): void
    {
        $this->statsd = $statsd;
    }
}
