<?php
declare(strict_types=1);

namespace App\Entity;
use GraphAware\Neo4j\OGM\Annotations as OGM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"fromId","toId","weight"})
 * @OGM\RelationshipEntity(type="CONNECTED")
 */
class Path
{
    /**
     * @var int
     * @Groups({"rest"})
     *
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var Node
     * @OA\Property(type="int")
     * @OGM\StartNode(targetEntity="Node")
     */
    protected $startNode;

    /**
     * @var Node
     * @OA\Property(type="int")
     * @OGM\EndNode(targetEntity="Node")
     */
    protected $endNode;

    /**
     * @OGM\Property(type="int")
     * @OA\Property(type="int")
     * @Groups({"rest"})
     * @var int
     */
    protected $weight;

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getStartNode(): Node
    {
        return $this->startNode;
    }
    
    public function setStartNode(Node $startNode): self
    {
        $this->startNode = $startNode;

        return $this;
    }
    
    public function getEndNode(): Node
    {
        return $this->endNode;
    }
    
    public function setEndNode(Node $endNode): self
    {
        $this->endNode = $endNode;

        return $this;
    }
    
    public function getWeight(): int
    {
        return $this->weight;
    }
    
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
