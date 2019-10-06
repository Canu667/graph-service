<?php
declare(strict_types=1);

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"fromId","toId"})
 */
class DisconnectDto
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
    
    public function __construct(int $fromId, int $toId)
    {
        $this->fromId = $fromId;
        $this->toId   = $toId;
    }

    public function getFromId(): int
    {
        return $this->fromId;
    }

    public function getToId(): int
    {
        return $this->toId;
    }
}
