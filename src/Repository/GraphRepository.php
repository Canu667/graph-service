<?php
declare(strict_types=1);

namespace App\Repository;
use App\Entity\Node;
use App\Entity\Path;
use GraphAware\Neo4j\OGM\EntityManagerInterface;

class GraphRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findNode(int $id)
    {
        return $this->em->find(Node::class, $id);
    }

    public function deleteNode(int $id)
    {
        $node = $this->findNode($id);

        $this->em->remove($node);
    }

    public function saveNode(Node $node): void
    {
        $this->em->persist($node);
        $this->em->flush();
    }

    public function deletePath(Path $path)
    {
        $this->em->remove($path);
        $this->em->flush();
    }

    public function getShortestRoute($fromNode, $toNode): ?array
    {
        $query = $this->em->createQuery(sprintf("MATCH (start), (end)
                                         WHERE ID(start) = %d AND ID(end) = %d 
                                         CALL algo.shortestPath.stream(start, end, 'weight', {direction:'OUTGOING'})
                                         YIELD nodeId, cost
                                         MATCH (other:Node) WHERE id(other) = nodeId
                                         RETURN other.name AS name, cost
                                         ", $fromNode, $toNode));
        $result = $query->execute();

        return $result;
    }
}
