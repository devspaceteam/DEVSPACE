<?php
// src/AppBundle/Command/GreetCommand.php
namespace PepiniereBundle\Command;



use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineBundle\ManagerConfigurator;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ServicesBundle\Controller\ReclamationController;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class GreetCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'demo:greet';
    protected function configure()
    {
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $c= new ReclamationController();
        $c->setContainer($this->getContainer());
        $c->auto_delete_from_trash();
        $c->auto_unban_user();

    }
}