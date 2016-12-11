<?php

namespace AppBundle\Controller;

use AppBundle\Document\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class HitController extends Controller
{
    /**
     * @Method({"POST"})
     * @Route("/hit/{id}", name="hit_handle", requirements={"id" = "\w+"})
     * @ParamConverter("project", class="AppBundle\Document\Project")
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response
     */
    public function handleAction(Request $request, Project $project)
    {
        $content = $request->getContent();

        $data = json_decode($content, $assoc = true);

        if ($data === false) {
            throw new UnsupportedMediaTypeHttpException();
        }

        $hitProcessor = $this->get('app.hit_processor');
        $hitProcessor->process($data, $project);

        return new Response();
    }
}
