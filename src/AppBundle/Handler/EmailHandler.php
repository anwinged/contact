<?php

namespace AppBundle\Handler;

use AppBundle\Document\Hit;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmailHandler implements HandlerInterface
{
    const DEFAULT_SUBJECT = 'Contact hit';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $fromEmail;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        string $fromEmail
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param Hit   $hit
     * @param array $configuration
     */
    public function handle(Hit $hit, array $configuration): void
    {
        $project = $hit->getProject();
        $user = $project->getUser();
        $data = $hit->getPayload();
        $subject = $configuration['subject'] ?? self::DEFAULT_SUBJECT;

        /* @var \Swift_Message $message */
        /* @noinspection PhpUndefinedMethodInspection */
        $message = \Swift_Message::newInstance()
            ->setTo($user->getEmail())
            ->setFrom($this->fromEmail)
            ->setSubject($subject)
            ->setBody($this->renderBody($data), 'text/html')
            ->addPart($this->renderPlain($data), 'text/plain')
            ->setCharset('utf-8')
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
        return [
            'subject' => [
                'label' => 'Subject',
                'type' => TextType::class,
            ],
        ];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function renderBody(array $data): string
    {
        return $this->twig->render('handler/email.html.twig', ['data' => $data]);
    }

    private function renderPlain(array $data): string
    {
        return $this->twig->render('handler/email.txt.twig', ['data' => $data]);
    }
}
