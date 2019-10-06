<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection as CollectionInterface;
use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"name"})
 * @OGM\Node(label="Node")
 */
class Node
{
    /**
     * @var int
     * @Groups({"rest"})
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var string
     * @OA\Property(type="string")
     * @Groups({"rest"})
     * @OGM\Property(type="string")
     */
    protected $name;

    /**
     * @var Path[]
     * @Groups({"rest"})
     * @OGM\Relationship(relationshipEntity="Path", type="CONNECTED", direction="INCOMING", collection=true, mappedBy="startNode")
     */
    protected $incomingPaths;

    /**
     * @var Path[]
     * @Groups({"rest"})
     * @OGM\Relationship(relationshipEntity="Path", type="CONNECTED", direction="OUTGOING", collection=true, mappedBy="endNode")
     */
    protected $outgoingPaths;

    public function __construct()
    {
        $this->incomingPaths = new Collection();
        $this->outgoingPaths = new Collection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIncomingPaths(): ?CollectionInterface
    {
        return $this->incomingPaths;
    }

    public function getOutgoingPaths(): ?CollectionInterface
    {
        return $this->outgoingPaths;
    }
}
