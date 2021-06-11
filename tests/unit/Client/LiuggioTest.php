<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Tests\Unit\Client;

use AdgoalCommon\Monitoring\Client\Liuggio;
use Liuggio\StatsdClient\Service\StatsdService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class StatsdTest.
 *
 * @covers       \AdgoalCommon\Monitoring\Client\Liuggio::__construct
 * @covers       \AdgoalCommon\Monitoring\Client\Liuggio::makeMetricName
 */
class LiuggioTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create and return StatsdService mock object.
     *
     * @return MockInterface|StatsdService
     */
    private function createStatsdClientMock(): MockInterface
    {
        $statsdClientMock = Mockery::mock(StatsdService::class);
        $statsdClientMock->shouldReceive('count');
        $statsdClientMock->shouldReceive('gauge');
        $statsdClientMock->shouldReceive('increment');
        $statsdClientMock->shouldReceive('timing');
        $statsdClientMock->shouldReceive('decrement');
        $statsdClientMock->shouldReceive('flush');
        $statsdClientMock->shouldReceive('updateCount');
        $statsdClientMock->shouldReceive('setSamplingRate');

        return $statsdClientMock;
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::increment
     */
    public function testIncrement(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->increment('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('increment')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::decrement
     */
    public function testDecrement(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->decrement('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('decrement')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::timing
     */
    public function testTiming(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->timing('eventName', 1000);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('timing')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::count
     */
    public function testCount(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->count('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('updateCount')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::gauge
     */
    public function testGauge(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->gauge('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('gauge')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::count
     */
    public function testMemory(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->memory('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('updateCount')->once();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::flush
     */
    public function testBatchingFlushTwice(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Liuggio($statsdServiceMock);
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('increment')->twice();
        $statsdServiceMock->shouldHaveReceived('setSamplingRate')->twice();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }
}
