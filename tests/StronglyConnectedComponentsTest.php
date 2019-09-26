<?php


namespace Vacilando\Tarjan\Tests;

use PHPUnit\Framework\TestCase;
use Vacilando\Tarjan\Edge;
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
        // Array $G is to contain the graph in node-adjacency format.
        $arrayGraphs = [
            0 => [
                0 => [1],
                1 => [4, 6, 7],
                2 => [4, 6, 7],
                3 => [4, 6, 7],
                4 => [2, 3],
                5 => [2, 3],
                6 => [5, 8],
                7 => [5, 8],
                8 => [],
                9 => [],
                10 => [10], // This is a self-cycle (aka "loop").
            ],
            1 => [
                0 => [2],
                2 => [6, 7],
                3 => [6, 7],
                6 => [8],
                7 => [8],
                8 => [0],
                9 => [0],
                10 => [10],
            ]
        ];

        foreach ($arrayGraphs as $arrayGraph) {
            $graph = new Graph();
            foreach ($arrayGraph as $startNodeId => $endNodeIds) {
                if (!count($endNodeIds)) {
                    $graph[$startNodeId] = [];
                    continue;
                }
                foreach ($endNodeIds as $endNodeId) {
                    $graph->addEdge((new Edge())
                        ->setStartNodeId($startNodeId)
                        ->setEndNodeId($endNodeId));
                }
            }
            $this->graphs[] = $graph;
        }
    }

    public function testGraph()
    {
        self::assertCount(11, array_shift($this->graphs));
        self::assertCount(8, array_pop($this->graphs));
    }

    public function testCycleThroughEntriesWithIndexGaps()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[1]))->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(3, $cycles, print_r($cycles, true));

        self::assertEquals('0|2|6|8', $cycles[0]);
        self::assertEquals('0|2|7|8', $cycles[1]);
        self::assertEquals('10', $cycles[2]);
    }

    public function testCycleThroughEntriesWithIndexGapsLimited()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[1]))
            ->setMaxLoopLength(3)
            ->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(1, $cycles, print_r($cycles, true));

        self::assertEquals('10', $cycles[0]);
    }

    public function testCycleThroughEntries()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[0]))->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(11, $cycles, print_r($cycles, true));

        self::assertEquals('2|4', $cycles[0]);
        self::assertEquals('2|4|3|6|5', $cycles[1]);
        self::assertEquals('2|4|3|7|5', $cycles[2]);
        self::assertEquals('2|6|5', $cycles[3]);
        self::assertEquals('2|6|5|3|4', $cycles[4]);
        self::assertEquals('2|7|5', $cycles[5]);
        self::assertEquals('2|7|5|3|4', $cycles[6]);
        self::assertEquals('3|4', $cycles[7]);
        self::assertEquals('3|6|5', $cycles[8]);
        self::assertEquals('3|7|5', $cycles[9]);
        self::assertEquals('10', $cycles[10]);
    }

    public function testCycleThroughEntriesLimited()
    {
        $cycles = (new StronglyConnectedComponents($this->graphs[0]))
            ->setMaxLoopLength(4)
            ->getConnectedComponents();

        self::assertIsArray($cycles);

        self::assertCount(7, $cycles, print_r($cycles, true));

        self::assertEquals('2|4', $cycles[0]);
        self::assertEquals('2|6|5', $cycles[1]);
        self::assertEquals('2|7|5', $cycles[2]);
        self::assertEquals('3|4', $cycles[3]);
        self::assertEquals('3|6|5', $cycles[4]);
        self::assertEquals('3|7|5', $cycles[5]);
        self::assertEquals('10', $cycles[6]);
    }

    public function testGetGiantComponent()
    {
        $giantComponent = (new StronglyConnectedComponents($this->graphs[0]))->getGiantComponent();

        self::assertIsString($giantComponent);

        self::assertEquals('2|4|3|6|5', $giantComponent);
    }
}
