<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Hello PhpStorm')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Password:');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The password can not be empty');
            }

            return $value;
        });
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $question->setMaxAttempts(3);

        $helper = $this->getHelper('question');

        $email = $input->getArgument('email');
        $plainPassword = $helper->ask($input, $output, $question);

        $userService = $this->getContainer()->get('app.user_service');
        $userService->createUser($email, $plainPassword);
    }
}
