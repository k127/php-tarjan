<?php


namespace Vacilando\Tarjan\Tests;

use PHPUnit\Framework\TestCase;
use Vacilando\Tarjan\Edge;
use Vacilando\Tarjan\Graph;
use Vacilando\Tarjan\StronglyConnectedComponents;

class StronglyConnectedComponentsTest extends TestCase
{
    /**
     * @var Graph
     */
    private $graph;

    public function setUp(): void
    {
        // Array $G is to contain the graph in node-adjacency format.
        $arrayGraph = [
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
        ];

        $this->graph = new Graph();

        foreach ($arrayGraph as $startNodeId => $endNodeIds) {
            if (!count($endNodeIds)) {
                $this->graph[$startNodeId] = [];
                continue;
            }
            foreach ($endNodeIds as $endNodeId) {
                $this->graph
                    ->addEdge((new Edge())
                        ->setStartNodeId($startNodeId)
                        ->setEndNodeId($endNodeId));
            }
        }
    }

    public function testGraph()
    {
        self::assertCount(11, $this->graph);
    }

    public function testCycleThroughEntries()
    {
        $s = new StronglyConnectedComponents($this->graph);

        $cycles = $s->cycleThroughEntries();

        /*
         * There are 11 results for the above example (strictly speaking: 10 cycles and 1 loop):
         * 2|4
         * 2|4|3|6|5
         * 2|4|3|7|5
         * 2|6|5
         * 2|6|5|3|4
         * 2|7|5
         * 2|7|5|3|4
         * 3|4
         * 3|6|5
         * 3|7|5
         * 10
         */

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
        $s = new StronglyConnectedComponents($this->graph);

        $cycles = $s->cycleThroughEntries(3);

        /*
         * There are 11 results for the above example (strictly speaking: 10 cycles and 1 loop):
         * 2|4
         * 2|4|3|6|5
         * 2|4|3|7|5
         * 2|6|5
         * 2|6|5|3|4
         * 2|7|5
         * 2|7|5|3|4
         * 3|4
         * 3|6|5
         * 3|7|5
         * 10
         */

        self::assertCount(10, $cycles, print_r($cycles, true));

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
    }
}
