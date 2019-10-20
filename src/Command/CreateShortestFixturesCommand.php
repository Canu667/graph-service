<?php
declare(strict_types=1);


namespace App\Command;

use App\Entity\Node;
use App\Entity\Path;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateShortestFixturesCommand extends Command
{
    protected static $defaultName = 'app:create-shortest-fixtures';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates fixtures for the shortest path in the graph DB')
            ->setHelp('Creates nodes and setups paths between them ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nodes = [];
        $nodeNames = ["Start", "Node1_1", "Node1_2", "Node2_1", "Node2_2", "Node3_1", "Node4_1", "End"];

        foreach ($nodeNames as $name)
        {
            $node = (new Node())->setName($name);
            $nodes[$name] = $node;
        }

        $this->connectNodes($nodes['Start'], $nodes['Node1_1'], 50);
        $this->connectNodes($nodes['Start'], $nodes['Node1_2'], 50);
        $this->connectNodes($nodes['Node1_1'], $nodes['Node2_1'], 50);
        $this->connectNodes($nodes['Node2_1'], $nodes['Node3_1'], 50);
        $this->connectNodes($nodes['Node3_1'], $nodes['Node4_1'], 50);
        $this->connectNodes($nodes['Node4_1'], $nodes['End'], 50);
        $this->connectNodes($nodes['Node1_2'], $nodes['Node2_2'], 50);
        $this->connectNodes($nodes['Node2_2'], $nodes['Node3_1'], 50);
        $this->connectNodes($nodes['Node2_2'], $nodes['End'], 50);

        foreach ($nodes as $key => $node)
        {
            $this->em->persist($node);
        }

        $this->em->flush();
    }

    private function connectNodes($fromNode, $toNode, $weight)
    {
        $path = (new Path())
            ->setStartNode($fromNode)
            ->setEndNode($toNode)
            ->setWeight($weight);

        $fromNode->getOutgoingPaths()->add($path);
        $toNode->getIncomingPaths()->add($path);
    }
}
