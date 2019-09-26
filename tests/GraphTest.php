<?php


namespace Vacilando\Tarjan\Tests;

use PHPUnit\Framework\TestCase;
use Vacilando\Tarjan\Graph;

/**
 * Class GraphTest
 * @package Vacilando\Tarjan\Tests
 */
class GraphTest extends TestCase
{
    const ARRAY_GRAPHS = [
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

    public function testFromArray()
    {
        $graph = (new Graph())->fromArray(self::ARRAY_GRAPHS[0]);

        self::assertCount(11, $graph, print_r($graph, true));

        self::assertEquals([1], $graph[0]);
        self::assertEquals([4, 6, 7], $graph[1]);
        self::assertEquals([4, 6, 7], $graph[2]);
        self::assertEquals([4, 6, 7], $graph[3]);
        self::assertEquals([2, 3], $graph[4]);
        self::assertEquals([2, 3], $graph[5]);
        self::assertEquals([5, 8], $graph[6]);
        self::assertEquals([5, 8], $graph[7]);
        self::assertEquals([], $graph[8]);
        self::assertEquals([], $graph[9]);
        self::assertEquals([10], $graph[10]);
    }

    public function testFromArrayLimited()
    {
        $graph = (new Graph())->fromArray(self::ARRAY_GRAPHS[1]);

        self::assertCount(8, $graph, print_r($graph, true));

        self::assertEquals([2], $graph[0]);
        self::assertEquals([6, 7], $graph[2]);
        self::assertEquals([6, 7], $graph[3]);
        self::assertEquals([8], $graph[6]);
        self::assertEquals([8], $graph[7]);
        self::assertEquals([0], $graph[8]);
        self::assertEquals([0], $graph[9]);
        self::assertEquals([10], $graph[10]);
    }
}
