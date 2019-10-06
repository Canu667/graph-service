<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\DisconnectDto;
use App\Dto\NodeDto;
use App\Dto\ConnectDto;
use App\Entity\Node;
use App\Entity\Path;
use App\Repository\GraphRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use OpenApi\Annotations as OA;

class NodeController extends AbstractFOSRestController
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
     * @OA\Post(
     *     operationId="addNode",
     *     description="Adds a node to the graph",
     *     tags={"Node"},
     *     path="/node",
     *     @OA\RequestBody(
     *         description="Adds a node to our graph",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NodeDto"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response",
     *      @OA\JsonContent(ref="#/components/schemas/Node")
     *     ),
     *   )
     * )
     *
     * @Rest\Post("/node")
     * @ParamConverter("nodeDto", converter="fos_rest.request_body")
     *
     * @param NodeDto                          $nodeDto
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     * @throws \Exception
     */
    public function createNode(NodeDto $nodeDto, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->sendBadResponse($validationErrors);
        }

        $node = (new Node())
            ->setName($nodeDto->getName());

        $this->nodeRepository->saveNode($node);

        return new Response(
            $this->serializer->serialize(
                $node,
                'json', [
                    'groups' => ['rest'],
                ]
            ));
    }

    /**
     * @OA\Get(
     *     operationId="nodeGet",
     *     description="Gets the node",
     *     tags={"Node"},
     *     path="/node",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the node",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response",
     *      @OA\JsonContent(ref="#/components/schemas/Node")
     *     ),
     *   )
     * )
     * @QueryParam(name="id", requirements="\d+")
     * @Rest\Get("/node/{id}")
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     */
    public function getNode(int $id)
    {
        return new Response(
            $this->serializer->serialize(
                $this->nodeRepository->findNode($id),
                'json', [
                    'groups' => ['rest'],
                ]
            ));
    }

    /**
     * @OA\Put(
     *     operationId="updateNode",
     *     description="Updates a node on the graph",
     *     tags={"Node"},
     *     path="/node",
     *     @OA\RequestBody(
     *         description="Updates a node on our graph",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NodeDto"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response",
     *      @OA\JsonContent(ref="#/components/schemas/Node")
     *     ),
     *   )
     * )
     *
     * @Rest\Put("/node")
     * @ParamConverter("nodeDto", converter="fos_rest.request_body")
     *
     * @param NodeDto                          $nodeDto
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     * @throws \Exception
     */
    public function updateNode(NodeDto $nodeDto, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->sendBadResponse($validationErrors);
        }

        $node = $this->nodeRepository->findNode($nodeDto->getId());
        $node->setName($nodeDto->getName());

        $this->nodeRepository->saveNode($node);

        return new Response(
            $this->serializer->serialize(
                $node,
                'json', [
                    'groups' => ['rest'],
                ]
            ));
    }

    /**
     * @OA\Delete(
     *     operationId="nodeDelete",
     *     description="Deletes the node",
     *     tags={"Node"},
     *     path="/node",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the node",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response"
     *     ),
     *   )
     * )
     * @QueryParam(name="id", requirements="\d+")
     * @Rest\Delete("/node/{id}")
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     */
    public function deleteNode(int $id)
    {
        $this->nodeRepository->deleteNode($id);

        return new Response();
    }

    /**
     * @OA\Post(
     *     operationId="connect",
     *     description="Connects two nodes",
     *     tags={"Path Node"},
     *     path="/node/connect",
     *     @OA\RequestBody(
     *         description="Adds a connection between two nodes",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ConnectDto"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response",
     *      @OA\JsonContent(ref="#/components/schemas/Path")
     *     ),
     *   )
     * )
     *
     * @Rest\Post("/node/connect")
     * @ParamConverter("connectDto", converter="fos_rest.request_body")
     *
     * @param ConnectDto                       $connectDto
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     * @throws \Exception
     */
    public function connectNodes(ConnectDto $connectDto, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->sendBadResponse($validationErrors);
        }

        $fromNode = $this->nodeRepository->findNode($connectDto->getFromId());
        $toNode = $this->nodeRepository->findNode($connectDto->getToId());

        $connectDto = (new Path())
            ->setStartNode($fromNode)
            ->setEndNode($toNode)
            ->setWeight($connectDto->getWeight());

        $fromNode->getOutgoingPaths()->add($connectDto);
        $toNode->getIncomingPaths()->add($connectDto);

        $this->nodeRepository->saveNode($fromNode);
        $this->nodeRepository->saveNode($toNode);

        return new Response(
            $this->serializer->serialize(
                $connectDto,
                'json', [
                    'groups' => ['rest'],
                ]
            ));
    }

    /**
     * @OA\Post(
     *     operationId="disconnect",
     *     description="Disconnects two nodes",
     *     tags={"Path Node"},
     *     path="/node/disconnect",
     *     @OA\RequestBody(
     *         description="Disconnects two nodes",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/DisconnectDto"
     *         )
     *     ),
     *     @OA\Response(
     *      response="200",
     *      description="Returns the response"
     *     ),
     *   )
     * )
     *
     * @Rest\Post("/node/disconnect")
     * @ParamConverter("disconnectDto", converter="fos_rest.request_body")
     *
     * @param DisconnectDto                       $disconnectDto
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return Response
     * @throws \Exception
     */
    public function disconnectNodes(DisconnectDto $disconnectDto, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->sendBadResponse($validationErrors);
        }

        $fromNode = $this->nodeRepository->findNode($disconnectDto->getFromId());
        $toNode = $this->nodeRepository->findNode($disconnectDto->getToId());

        $pathDoDelete = null;
        foreach ($fromNode->getOutgoingPaths() as $outgoingPath) {
            foreach ($toNode->getIncomingPaths() as $incomingPath) {
                if ($incomingPath === $outgoingPath) {
                    $pathDoDelete = $outgoingPath;
                }
            }
        }

        $fromNode->getOutgoingPaths()->removeElement($pathDoDelete);
        $toNode->getIncomingPaths()->removeElement($pathDoDelete);

        $this->nodeRepository->saveNode($fromNode);
        $this->nodeRepository->saveNode($toNode);

        $this->nodeRepository->deletePath($pathDoDelete);

        return new Response();
    }

    private function sendBadResponse(ConstraintViolationListInterface $validationErrors)
    {
        $view = $this->view($validationErrors, Response::HTTP_BAD_REQUEST);

        return $this->handleView($view);
    }

}
