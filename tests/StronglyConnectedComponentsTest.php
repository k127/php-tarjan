<?php


namespace Vacilando\Tarjan\Tests;

use PHPUnit\Framework\TestCase;
use Vacilando\Tarjan\Graph;
use Vacilando\Tarjan\StronglyConnectedComponents;

/**
 * Class StronglyConnectedComponentsTest
 * @package Vacilando\Tarjan\Tests
 */
class StronglyConnectedComponentsTest extends TestCase
{
    /**
     * @var Graph[]
     */
    private $graphs = [];

    public function setUp(): void
    {
        foreach (GraphTest::ARRAY_GRAPHS as $arrayGraph) {
            $this->graphs[] = (new Graph())->fromArray($arrayGraph);
        }
    }

    public function testGraph()
    {
        self::assertCount(11, $this->graphs[0]);
        self::assertCount(8, $this->graphs[1]);
    }

    public function testCycleThroughEntriesWithIndexGaps()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[1]))->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(3, $cycles, print_r($cycles, true));

        self::assertEquals([0, 2, 6, 8], $cycles[0]);
        self::assertEquals([0, 2, 7, 8], $cycles[1]);
        self::assertEquals([10], $cycles[2]);
    }

    public function testCycleThroughEntries()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[0]))->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(11, $cycles, print_r($cycles, true));

        self::assertEquals([2, 4], $cycles[0]);
        self::assertEquals([2, 4, 3, 6, 5], $cycles[1]);
        self::assertEquals([2, 4, 3, 7, 5], $cycles[2]);
        self::assertEquals([2, 6, 5], $cycles[3]);
        self::assertEquals([2, 6, 5, 3, 4], $cycles[4]);
        self::assertEquals([2, 7, 5], $cycles[5]);
        self::assertEquals([2, 7, 5, 3, 4], $cycles[6]);
        self::assertEquals([3, 4], $cycles[7]);
        self::assertEquals([3, 6, 5], $cycles[8]);
        self::assertEquals([3, 7, 5], $cycles[9]);
        self::assertEquals([10], $cycles[10]);
    }

    public function testCycleThroughEntries2()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[2]))->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(0, $cycles, print_r($cycles, true));
    }

    public function testGetGiantComponent()
    {
        $giantComponent = (new StronglyConnectedComponents($this->graphs[0]))->getGiantComponent();

        self::assertIsArray($giantComponent);

        self::assertEquals([2, 4, 3, 6, 5], $giantComponent);
    }
}
