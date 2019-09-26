# tarjan.php

## PHP implementation of Tarjan's strongly connected components algorithm

[Tarjan's strongly connected components algorithm](https://en.wikipedia.org/wiki/Tarjan%27s_strongly_connected_components_algorithm), published in paper Enumeration of the Elementary Circuits of a Directed Graph by Robert Tarjan in 1972, is a graph theory (link is external) algorithm for detecting all cycles and loops (edges connecting vertices to themselves) in a directed graph.

It performs a single pass of depth-first search. It maintains a stack of vertices that have been explored by the search but not yet assigned to a component, and calculates "low numbers" of each vertex (an index number of the highest ancestor reachable in one step from a descendant of the vertex) which it uses to determine when a set of vertices should be popped off the stack into a new component.

It is trivial to write algorithms to detect cycles in a graph, but most approaches prove highly impractical due to the immense time and storage they require to complete the computation. Tarjan's algorithm is one of the precious few that is able to compute strongly connected components in linear time (time increases linearly with the number of vertices and edges).
The others include Kosaraju's algorithm and the path-based strong component algorithm (Purdo, Munro, Dijkstra, Cheriyan & Mehlhorn, Gabow). Although Kosaraju's algorithm is conceptually simple, Tarjan's and the path-based algorithm are favoured in practice since they require only one depth-first search rather than two.

The PHP implementation of Tarjan's algorithm below has been composed and tuned after much reading, trial and error, and peeking at implementations in numerous other programming languages. Special thanks to Jan van der Linde ([@janvdl](https://github.com/janvdl)) for helpful remarks and code snippets from his Python implementation.

## More information and live demo

[http://www.vacilando.org/article/php-implementation-tarjans-cycle-detection-algorithm](http://www.vacilando.org/article/php-implementation-tarjans-cycle-detection-algorithm)

## Installation

(While not on packagist.org, add the following repository to your `composer.json`:)

```json
{
    "repositories": [
        {"type": "github", "url": "https://github.com/k127/php-tarjan.git"}
    ]
}
```
…then execute…
```bash
composer require k127/tarjan
```

## Usage

Input: an array of vertex-children lists: `[[1,2,3], [5,6,7]]` means vertex 0 points to vertices 1, 2, and 3, while vertex 1 points to vertices 5, 6, and 7.

Output: an array of cycles: `[[2,3,5,2], [5,6,5], [3,7,9,3]]` contains three cycles. The first goes from 2 to 3 to 5 back to 2.

```php
use Vacilando\Tarjan\Edge;
use Vacilando\Tarjan\Graph;
use Vacilando\Tarjan\StronglyConnectedComponents;

$graph = ( new Graph() )
    ->addEdge(( new Edge() )->setStartNodeId(0)->setEndNodeId(2))
    ->addEdge(( new Edge() )->setStartNodeId(2)->setEndNodeId(6))
    ->addEdge(( new Edge() )->setStartNodeId(2)->setEndNodeId(7))
    ->addEdge(( new Edge() )->setStartNodeId(3)->setEndNodeId(6))
    ->addEdge(( new Edge() )->setStartNodeId(3)->setEndNodeId(7))
    ->addEdge(( new Edge() )->setStartNodeId(6)->setEndNodeId(8))
    ->addEdge(( new Edge() )->setStartNodeId(7)->setEndNodeId(8))
    ->addEdge(( new Edge() )->setStartNodeId(8)->setEndNodeId(0))
    ->addEdge(( new Edge() )->setStartNodeId(9)->setEndNodeId(0))
    ->addEdge(( new Edge() )->setStartNodeId(10)->setEndNodeId(10));

print_r($cycles = ( new StronglyConnectedComponents($graph) )->getConnectedComponents());
```
