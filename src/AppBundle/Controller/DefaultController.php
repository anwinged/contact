<?php

namespace AppBundle\Controller;

use AppBundle\Document\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('default/index.html.twig', [
            'user' => $user,
        ]);
    }
}
