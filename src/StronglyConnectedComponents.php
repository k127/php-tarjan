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
     * @var int
     */
    private $maxLoopLength;

    /**
     * StronglyConnectedComponents constructor.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
        $this->maxLoopLength = 0;

        // Initialize global values that are so far undefined.
        $this->cycles = [];
        $this->marked = [];
        $this->markedStack = [];
        $this->pointStack = [];
    }

    /**
     * @return string|null
     */
    public function getGiantComponent(): ?string
    {
        $maxNodes = 0;
        $longestPathStr = null;
        foreach ($this->getConnectedComponents() as $pathStr) {
            if (($nodeCount = count($path = explode('|', $pathStr))) > $maxNodes) {
                $maxNodes = $nodeCount;
                $longestPathStr = $pathStr;
            }
        }
        return $longestPathStr;
    }

    /**
     * Iterates through the graph array rows, executing php_tarjan().
     *
     * @return array
     */
    public function getConnectedComponents(): array
    {
        foreach ($this->graph as $startNodeId => $endNodeIdList) {
            $this->marked[$startNodeId] = false;
        }

        //$i = 0;
        foreach ($this->graph as $startNodeId => $endNodeIdList) {
            $this->tarjan($startNodeId, $startNodeId);
            while (!empty($this->markedStack)) {
                $this->marked[array_pop($this->markedStack)] = false;
            }
            //echo "\n" . ++$i . ' / ' . count($this->graph); // Enable if you wish to follow progression through the array rows.
        }

        $this->cycles = array_keys($this->cycles);

        return $this->cycles;
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
        $this->pointStack[] = $currentNodeId;
        $this->marked[$currentNodeId] = true;
        $this->markedStack[] = $currentNodeId;

        foreach ($this->graph[$currentNodeId] as $currentEndNodeId) {
            if ($currentEndNodeId < $FirstNodeId) {
                $this->graph[$currentEndNodeId] = [];
            } elseif ($currentEndNodeId == $FirstNodeId) {
                if (!$this->maxLoopLength || count($this->pointStack) <= $this->maxLoopLength) { // collect cycles of a given length only.
                    // Add new cycles as array keys to avoid duplication. Way faster than using array_search.
                    $this->cycles[implode('|', $this->pointStack)] = true;
                }
                $finished = true;
            } elseif ($this->marked[$currentEndNodeId] === false) {
                if (!$this->maxLoopLength || count($this->pointStack) < $this->maxLoopLength) { // only collect cycles up to $maxLoopLength.
                    $recurseFinished = $this->tarjan($FirstNodeId, $currentEndNodeId);
                }
                if (!empty($finished) || !empty($recurseFinished)) {
                    $finished = true;
                }
            }
        }

        if ($finished === true) {
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
