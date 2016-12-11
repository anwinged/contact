<?php

namespace AppBundle\Controller;

use AppBundle\Document\Hit;
use AppBundle\Document\Project;
use AppBundle\Repository\HitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * @Route("/project/create", name="project_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $project->setUser($this->getUser());

        $projectForm = $this->createFormBuilder($project)
            ->add('name', TextType::class)
            ->getForm();

        $projectForm->handleRequest($request);

        if ($projectForm->isValid()) {
            $om = $this->get('doctrine_mongodb')->getManager();
            $om->persist($project);
            $om->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('project/create.html.twig', [
            'formView' => $projectForm->createView(),
        ]);
    }

    /**
     * @Route("/project/view/{id}", name="project_view", requirements={"id" = "\w+"})
     * @ParamConverter("project", class="AppBundle\Document\Project")
     * @Security("is_granted('ACCESS', project)")
     *
     * @param Project $project
     *
     * @return Response
     */
    public function viewAction(Project $project)
    {
        $handlerManager = $this->get('app.handler_manager');

        /** @var HitRepository $hitRepository */
        $hitRepository = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository(Hit::class)
        ;

        return $this->render('project/view.html.twig', [
            'project' => $project,
            'handlers' => $handlerManager->getHandlers(),
            'newestHits' => $hitRepository->findNewest($project),
        ]);
    }
}
