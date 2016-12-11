<?php

namespace AppBundle\Handler;

use AppBundle\Document\Hit;

class EmailHandler implements HandlerInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Hit   $hit
     * @param array $configuration
     */
    public function handle(Hit $hit, array $configuration): void
    {
        $email = $configuration['email'] ?? null;

        $message = \Swift_Message::newInstance()
            ->setTo($email)
            ->setFrom('contact@anwinged.ru')
            ->setBody('Yes!', 'text/plain')
        ;

        $this->mailer->send($message);
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return 'Email';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getCaption();
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return ['email'];
    }
}
