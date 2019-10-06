<?php
declare(strict_types=1);

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(required={"name"})
 */
class NodeDto
{
    /**
     * @OA\Property(type="int")
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(type="string")
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @var string
     */
    private $name;
    
    public function __construct(int $id = null, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
}
