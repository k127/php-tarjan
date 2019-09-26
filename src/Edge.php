<?php


namespace Vacilando\Tarjan;

/**
 * Class Edge
 * @package Vacilando\Tarjan
 */
class Edge
{
    /**
     * @var int
     */
    private $startNodeId, $endNodeId;

    /**
     * @return int
     */
    public function getStartNodeId(): int
    {
        return $this->startNodeId;
    }

    /**
     * @param int $startNodeId
     * @return Edge
     */
    public function setStartNodeId(int $startNodeId): Edge
    {
        $this->startNodeId = $startNodeId;

        return $this;
    }

    /**
     * @return int
     */
    public function getEndNodeId(): int
    {
        return $this->endNodeId;
    }

    /**
     * @param int $endNodeId
     * @return Edge
     */
    public function setEndNodeId(int $endNodeId): Edge
    {
        $this->endNodeId = $endNodeId;

        return $this;
    }
}
