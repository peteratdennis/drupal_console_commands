<?php

/**
 * @file
 * Contains \Drupal\Console\Extend\Command\ExampleCommand
 */

namespace DennisDigital\Drupal\Console\Command\Site;

use Drupal\Console\Core\Style\DrupalStyle;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;


/**
 * Class FooCommand
 * @package Drupal\Console\Extend\Command
 */
class FooCommand extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('site:foo')
            ->setDescription('Site foo example ')
            ->setDefinition(
            new InputDefinition(array(
              new InputOption('checkout'),
              new InputOption('make'),
              new InputOption('npm'),

            ))
          );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new DrupalStyle($input, $output);
        //$io->commentBlock('Gonna do stuff, maybe.');
        //$io->warning('This is a warning');

      //$io->commentBlock($input->getOption('checkout'));


        if ($input->getOption('checkout') == TRUE) {
          //var_dump($input->getOption('checkout')); exit;
          $io->comment('Gonna run site:checkout for ya');
          //$command = $this->getApplication()->find('site:checkout');
          //$command->run($input, $output);
        }

//        if ($input->getOption('make') == TRUE) {
//          $io->comment('Gonna run site:make for ya');
//        }
//
//        if ($input->getOption('npm') == TRUE) {
//          $io->comment('Gonna run site:npm for ya');
//        }

//      $command = $this->getApplication()->find('site:make');
//      $command->run($input, $output);
    }
}
