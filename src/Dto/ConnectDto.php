<?php
declare(strict_types=1);


namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"fromId","toId","weight"})
 */
class ConnectDto
{
    /**
     * @OA\Property(type="int")
     * @Assert\Type("int")
     * @Assert\NotNull()
     *
     * @var int
     */
    private $fromId;

    /**
     * @OA\Property(type="int")
     * @Assert\Type("int")
     * @Assert\NotNull()
     *
     * @var int
     */
    private $toId;

    /**
     * @OA\Property(type="int")
     * @Assert\Type("int")
     * @Assert\NotNull()
     *
     * @var int
     */
    private $weight;

    public function __construct(int $fromId, int $toId, int $weight)
    {
        $this->fromId = $fromId;
        $this->toId   = $toId;
        $this->weight = $weight;
    }

    public function getFromId(): int
    {
        return $this->fromId;
    }

    public function getToId(): int
    {
        return $this->toId;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
