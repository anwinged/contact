<?php

namespace AppBundle\Controller;

use AppBundle\Document\Catcher;
use AppBundle\Document\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/catcher")
 */
class CatcherController extends Controller
{
    /**
     * @Route("/create/{id}/alias/{alias}", name="catcher_create", requirements={"id" = "\w+", "alias" = "\w+"})
     * @ParamConverter("project", class="AppBundle\Document\Project")
     * @Security("is_granted('ACCESS', project)")
     *
     * @param Request $request
     * @param Project $project
     * @param string  $alias
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, Project $project, string $alias)
    {
        $handlerManager = $this->get('app.handler_manager');
        $handler = $handlerManager->getHandler($alias);

        if ($handler === null) {
            $this->createNotFoundException('Handler not found');
        }

        $catcher = new Catcher();
        $catcher->setProject($project);
        $catcher->setHandlerAlias($alias);

        $catcherFormBuilder = $this->get('app.form_builder.catcher');
        $form = $catcherFormBuilder->buildForm($catcher, $handler);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $om = $this->get('doctrine_mongodb')->getManager();
            $om->persist($catcher);
            $om->flush();

            return $this->redirectToRoute('project_view', ['id' => $project->getId()]);
        }

        return $this->render('catcher/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
