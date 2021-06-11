<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Tests\Unit;

use AdgoalCommon\Monitoring\Client\ClientInterface;
use AdgoalCommon\Monitoring\Statsd;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class StatsdTest.
 *
 * @covers       \AdgoalCommon\Monitoring\Statsd::__construct
 * @covers       \AdgoalCommon\Monitoring\Statsd::makeMetricName
 */
class StatsdTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * Create and return ClientInterface mock object.
     *
     * @return MockInterface|ClientInterface
     */
    private function createStatsdClientMock(): MockInterface
    {
        $statsdClientMock = Mockery::mock(ClientInterface::class);
        $statsdClientMock->shouldReceive('count');
        $statsdClientMock->shouldReceive('gauge');
        $statsdClientMock->shouldReceive('increment');
        $statsdClientMock->shouldReceive('timing');
        $statsdClientMock->shouldReceive('decrement');
        $statsdClientMock->shouldReceive('flush');
        $statsdClientMock->shouldReceive('memory');

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
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->increment('eventName');
        $statsdServiceMock->shouldHaveReceived('increment')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::decrement
     */
    public function testDecrement(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->decrement('eventName');
        $statsdServiceMock->shouldHaveReceived('decrement')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::timing
     */
    public function testTiming(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->timing('eventName', 1000);
        $statsdServiceMock->shouldHaveReceived('timing')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::count
     */
    public function testCount(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->count('eventName', 10);
        $statsdServiceMock->shouldHaveReceived('count')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::gauge
     */
    public function testGauge(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->gauge('eventName', 10);
        $statsdServiceMock->shouldHaveReceived('gauge')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::memory
     */
    public function testMemory(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->memory('eventName');
        $statsdServiceMock->shouldHaveReceived('memory')->once();
        $statsdServiceMock->shouldNotHaveReceived('flush');
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::flush
     */
    public function testBatchingFlushTwice(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 3);
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        unset($statsd);
        $statsdServiceMock->shouldHaveReceived('increment')->times(4);
        $statsdServiceMock->shouldHaveReceived('flush')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::flush
     */
    public function testBatchingFlushOnce(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 3);
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        unset($statsd);
        $statsdServiceMock->shouldHaveReceived('increment')->times(3);
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::flushNow
     */
    public function testBatchingFlushImmediately(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->flushNow();
        $statsdServiceMock->shouldHaveReceived('increment')->times(3);
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::__destruct
     */
    public function testDestruct(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Statsd($statsdServiceMock, 'prefix', 5);
        $statsd->increment('eventName');
        unset($statsd);
        $statsdServiceMock->shouldHaveReceived('increment')->once();
        $statsdServiceMock->shouldHaveReceived('flush')->once();
    }
}
