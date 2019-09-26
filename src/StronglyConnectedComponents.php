<?php


namespace Vacilando\Tarjan;

/**
 * Class StronglyConnectedComponents
 * @package Vacilando\Tarjan
 */
class StronglyConnectedComponents
{
    private
        $cycles,
        $marked,
        $markedStack,
        $pointStack;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * StronglyConnectedComponents constructor.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @return array
     */
    public function getGiantComponent(): array
    {
        $maxNodes = 0;
        $longestPath = [];
        foreach ($this->getConnectedComponents() as $path) {
            if (($nodeCount = count($path)) > $maxNodes) {
                $maxNodes = $nodeCount;
                $longestPath = $path;
            }
        }

        return $longestPath;
    }

    /**
     * Iterates through the graph array rows, executing php_tarjan().
     *
     * @return array
     */
    public function getConnectedComponents(): array
    {
        $this->reset();

        $this->graph->ksort();

        foreach (array_keys((array)$this->graph) as $startNodeId) {
            $this->marked[$startNodeId] = false;
        }

        //$i = 0;
        // Run a depth-first search on all vertices in the graph
        foreach ($this->graph as $startNodeId => $endNodeIdList) {
            $this->tarjan($startNodeId, $startNodeId);
            while (count($this->markedStack)) {
                $this->marked[array_pop($this->markedStack)] = false;
            }
            //echo "\n" . ++$i . ' / ' . count($this->graph); // Enable if you wish to follow progression through the array rows.
        }

        // collect cycles. The tarjan algorithm outputs a cycle of three vertices as 4, 5, 6;
        // we want 4, 5, 6, 4 (i.e. append the start to the loop)
        /*
        $filteredCycles = [];
        foreach ($this->cycles as $cycle) {
            $cycle[] = $cycle[0];
            $filteredCycles[] = $cycle;
        }

        return $filteredCycles;
         */

        return $this->cycles;
    }

    private function reset()
    {
        // Initialize global values that are so far undefined.
        $this->cycles = [];
        $this->marked = [];
        $this->markedStack = [];
        $this->pointStack = [];
    }

    /**
     * Recursive function to detect strongly connected components (cycles, loops).
     *
     * @param int $FirstNodeId
     * @param int $currentNodeId
     *
     * @return bool
     */
    private function tarjan(int $FirstNodeId, int $currentNodeId): bool
    {
        $finished = false;

        // begin collecting whether vertices have been visited. Point stacks are the current cycle.
        $this->pointStack[] = $currentNodeId;
        $this->marked[$currentNodeId] = true;
        $this->markedStack[] = $currentNodeId;

        // for each vertex-index reachable by this vertex
        foreach ($this->graph[$currentNodeId] as $child) {
            if ($child < $FirstNodeId) {
                $this->graph[$child] = [];
            } elseif ($child == $FirstNodeId) {
                // Add new cycles as array keys to avoid duplication. Way faster than using array_search.
                $this->cycles[] = $this->pointStack;
                $finished = true;
            } elseif ($this->marked[$child] == false) { // continue exploring down the graph, forward, to find visited nodes
                if ($this->tarjan($FirstNodeId, $child)) {
                    $finished = true;
                }
            }
        }

        if ($finished) {
            while (end($this->markedStack) != $currentNodeId) {
                $this->marked[array_pop($this->markedStack)] = false;
            }
            array_pop($this->markedStack);
            $this->marked[$currentNodeId] = false;
        }

        array_pop($this->pointStack);

        return $finished;
    }

    /**
     * @param int $maxLoopLength
     * @return StronglyConnectedComponents
     */
    public function setMaxLoopLength(int $maxLoopLength): StronglyConnectedComponents
    {
        $this->maxLoopLength = $maxLoopLength;

        return $this;
    }
}
