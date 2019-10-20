<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\GraphRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

class PathController extends AbstractFOSRestController
{
    /**
     * @var GraphRepository
     */
    protected $nodeRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(GraphRepository $nodeRepository, SerializerInterface $serializer)
    {
        $this->nodeRepository = $nodeRepository;
        $this->serializer     = $serializer;
    }

    /**
     * @OA\Get(
     *     operationId="shortestPath",
     *     description="Gets the shortestPath between nodes",
     *     tags={"path"},
     *     path="/shortest-path/{fromId}/{toId}",
     *     @OA\Parameter(
     *         name="fromId",
     *         in="path",
     *         description="The id of the node",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="toId",
     *         in="path",
     *         description="The id of the node",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="JSON response with points",
     *     ),
     *   )
     * )
     * @QueryParam(name="fromId", requirements="\d+")
     * @QueryParam(name="toId", requirements="\d+")
     * @Rest\Get("/shortest-path/{fromId}/{toId}")
     *
     * @param int $fromId
     * @param int $toId
     *
     * @return Response
     */
    public function getShortestPath(int $fromId, int $toId)
    {
        return new JsonResponse($this->nodeRepository->getShortestRoute($fromId, $toId));
    }
}
