<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     *
     * @Route("/admin", name="admin")
     *
     * @return Response
     */
    public function adminAction(Request $request)
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
