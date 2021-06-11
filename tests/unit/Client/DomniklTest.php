<?php

declare(strict_types=1);

namespace AdgoalCommon\Monitoring\Tests\Unit\Client;

use AdgoalCommon\Monitoring\Client\Domnikl;
use Domnikl\Statsd\Client;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class DomniklTest.
 *
 * @covers       \AdgoalCommon\Monitoring\Client\Domnikl::__construct
 * @covers       \AdgoalCommon\Monitoring\Client\Domnikl::makeMetricName
 */
class DomniklTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create and return Client mock object.
     *
     * @return MockInterface|Client
     */
    private function createStatsdClientMock(): MockInterface
    {
        $statsdClientMock = Mockery::mock(Client::class);
        $statsdClientMock->shouldReceive('count');
        $statsdClientMock->shouldReceive('gauge');
        $statsdClientMock->shouldReceive('increment');
        $statsdClientMock->shouldReceive('timing');
        $statsdClientMock->shouldReceive('decrement');
        $statsdClientMock->shouldReceive('flush');
        $statsdClientMock->shouldReceive('count');
        $statsdClientMock->shouldReceive('memory');
        $statsdClientMock->shouldReceive('endBatch');
        $statsdClientMock->shouldReceive('startBatch');

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
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->increment('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('increment')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::decrement
     */
    public function testDecrement(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->decrement('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('decrement')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::timing
     */
    public function testTiming(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->timing('eventName', 1000);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('timing')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::count
     */
    public function testCount(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->count('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('count')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::gauge
     */
    public function testGauge(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->gauge('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('gauge')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::count
     */
    public function testMemory(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->memory('eventName', 10);
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('memory')->once();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }

    /**
     * @group        unit
     *
     * @covers       \AdgoalCommon\Monitoring\Statsd::flush
     */
    public function testBatchingFlushTwice(): void
    {
        $statsdServiceMock = $this->createStatsdClientMock();
        $statsd = new Domnikl($statsdServiceMock);
        $statsd->increment('eventName');
        $statsd->increment('eventName');
        $statsd->flush();
        $statsdServiceMock->shouldHaveReceived('increment')->twice();
        $statsdServiceMock->shouldHaveReceived('endBatch')->once();
        $statsdServiceMock->shouldHaveReceived('startBatch')->twice();
    }
}
