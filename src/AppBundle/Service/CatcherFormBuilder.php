<?php

namespace AppBundle\Service;

use AppBundle\Document\Catcher;
use AppBundle\Handler\HandlerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class CatcherFormBuilder
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param Catcher          $catcher
     * @param HandlerInterface $handler
     *
     * @return FormInterface
     */
    public function buildForm(Catcher $catcher, HandlerInterface $handler)
    {
        $formBuilder = $this->formFactory
            ->createBuilder(FormType::class, $catcher)
            ->add('target', TextType::class)
        ;

        foreach ($handler->getConfiguration() as $name => $params) {
            $fullName = sprintf('handlerConfiguration_%s', $name);
            $type = $params['type'] ?? TextType::class;
            $formBuilder->add($fullName, $type, [
                'label' => $params['label'] ?? $name,
                'property_path' => sprintf('handlerConfiguration[%s]', $name),
            ]);
        }

        return $formBuilder->getForm();
    }
}
