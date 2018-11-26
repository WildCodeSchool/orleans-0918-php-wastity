<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/association")
 */
class AssociationController extends AbstractController
{
    /**
     * @Route("/", name="association_index", methods="GET")
     */
    public function index(AssociationRepository $associationRepository): Response
    {
        return $this->render('Visitor/Association/index.html.twig', [
            'associations' => $associationRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="association_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($association);
            $em->flush();

            return $this->redirectToRoute('association_index');
        }

        return $this->render('Visitor/Association/new.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="association_show", methods="GET")
     */
    public function show(Association $association): Response
    {
        return $this->render('Visitor/Association/show.html.twig', ['association' => $association]);
    }

    /**
     * @Route("/{id}/edit", name="association_edit", methods="GET|POST")
     */
    public function edit(Request $request, Association $association): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('association_index', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="association_delete", methods="DELETE")
     */
    public function delete(Request $request, Association $association): Response
    {
        if ($this->isCsrfTokenValid('delete' . $association->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($association);
            $em->flush();
        }

        return $this->redirectToRoute('association_index');
    }
}
