<?php


namespace Vacilando\Tarjan;

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

        // Initialize global values that are so far undefined.
        $this->cycles = [];
        $this->marked = [];
        $this->markedStack = [];
        $this->pointStack = [];
    }

    /**
     * Iterates through the graph array rows, executing php_tarjan().
     *
     * @return array
     */
    public function cycleThroughEntries(int $maxLoopLength = 0): array
    {
        for ($x = 0; $x < count($this->graph); $x++) {
            $this->marked[$x] = false;
        }

        for ($i = 0; $i < count($this->graph); $i++) {
            $this->tarjan($i, $i, $maxLoopLength);
            while (!empty($this->markedStack)) {
                $this->marked[array_pop($this->markedStack)] = false;
            }
            //echo '<br>'.($i+1).' / '.count($G_local); // Enable if you wish to follow progression through the array rows.
        }

        $this->cycles = array_keys($this->cycles);

        return $this->cycles;
    }

    /**
     * Recursive function to detect strongly connected components (cycles, loops).
     *
     * @param int $s
     * @param int $v
     *
     * @return bool
     */
    private function tarjan(int $s, int $v, int $maxLoopLength = 0): bool
    {
        $f = false;
        $this->pointStack[] = $v;
        $this->marked[$v] = true;
        $this->markedStack[] = $v;

        foreach ($this->graph[$v] as $w) {
            if ($w < $s) {
                $this->graph[$w] = [];
            } elseif ($w == $s) {
                if (!$maxLoopLength || count($this->pointStack) == $maxLoopLength) { // collect cycles of a given length only.
                    // Add new cycles as array keys to avoid duplication. Way faster than using array_search.
                    $this->cycles[implode('|', $this->pointStack)] = true;
                }
                $f = true;
            } elseif ($this->marked[$w] === false) {
                if (!$maxLoopLength || count($this->pointStack) < $maxLoopLength) { // only collect cycles up to $maxLoopLength.
                    $g = $this->tarjan($s, $w);
                }
                if (!empty($f) OR !empty($g)) {
                    $f = true;
                }
            }
        }

        if ($f === true) {
            while (end($this->markedStack) != $v) {
                $this->marked[array_pop($this->markedStack)] = false;
            }
            array_pop($this->markedStack);
            $this->marked[$v] = false;
        }

        array_pop($this->pointStack);

        return $f;
    }
}
