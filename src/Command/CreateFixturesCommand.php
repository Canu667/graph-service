<?php
declare(strict_types=1);


namespace App\Command;

use App\Entity\Node;
use App\Entity\Path;
use GraphAware\Neo4j\OGM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFixturesCommand extends Command
{
    protected static $defaultName = 'app:create-fixtures';

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
            ->setDescription('Creates fixtures in the graph DB')
            ->setHelp('Creates nodes and setups paths between them ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nodes = [];
        $nodeNames = ["2", "3", "5", "7", "8", "9", "10", "11"];

        foreach ($nodeNames as $name)
        {
            $node = (new Node())->setName($name);
            $nodes[$name] = $node;
        }

        $this->connectNodes($nodes['11'], $nodes['2'], 50);
        $this->connectNodes($nodes['11'], $nodes['9'], 50);
        $this->connectNodes($nodes['11'], $nodes['10'], 50);
        $this->connectNodes($nodes['5'], $nodes['11'], 50);
        $this->connectNodes($nodes['7'], $nodes['11'], 50);
        $this->connectNodes($nodes['7'], $nodes['8'], 50);
        $this->connectNodes($nodes['8'], $nodes['9'], 50);
        $this->connectNodes($nodes['3'], $nodes['10'], 50);
        $this->connectNodes($nodes['3'], $nodes['8'], 50);

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
