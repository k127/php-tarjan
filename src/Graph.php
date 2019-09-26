<?php


namespace Vacilando\Tarjan;

use ArrayObject;

class Graph extends ArrayObject
{
    /**
     * Graph constructor.
     *
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        $data = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $value = new self($value);
            }
            $data[$key] = $value;
        }
        parent::__construct($data, $flags, $iterator_class);
    }

    /**
     * @param $arrayGraph
     *
     * @return Graph
     */
    public function fromArray($arrayGraph): self
    {
        foreach ($arrayGraph as $startNodeId => $endNodeIds) {
            if (!count($endNodeIds)) {
                $this[$startNodeId] = [];
                continue;
            }
            foreach ($endNodeIds as $endNodeId) {
                $this->addEdge((new Edge())
                    ->setStartNodeId($startNodeId)
                    ->setEndNodeId($endNodeId));
            }
        }

        return $this;
    }

    /**
     * @param Edge $edge
     * @return Graph
     */
    public function addEdge(Edge $edge): self
    {
        if (array_key_exists($startNodeId = $edge->getStartNodeId(), $this)) {
            array_push($this[$startNodeId], $edge->getEndNodeId());
        } else {
            $this[$startNodeId] = [$edge->getEndNodeId()];
        }

        return $this;
    }
}
