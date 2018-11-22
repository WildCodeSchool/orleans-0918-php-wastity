<?php

namespace App\Controller;

use App\Entity\Compagny;
use App\Form\CompagnyType;
use App\Repository\CompagnyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compagny")
 */
class CompagnyController extends AbstractController
{
    /**
     * @Route("/", name="compagny_index", methods="GET")
     * @param CompagnyRepository $compagnyRepository
     * @return Response
     */
    public function index(CompagnyRepository $compagnyRepository): Response
    {
        return $this->render('compagny/index.html.twig', ['compagnyes' => $compagnyRepository->findAll()]);
    }

    /**
     * @Route("/new", name="compagny_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $compagny = new Compagny();
        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compagny);
            $em->flush();

            return $this->redirectToRoute('compagny_index');
        }

        return $this->render('compagny/new.html.twig', [
            'compagny' => $compagny,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compagny_show", methods="GET")
     */
    public function show(Compagny $compagny): Response
    {
        return $this->render('compagny/show.html.twig', ['compagny' => $compagny]);
    }

    /**
     * @Route("/{id}/edit", name="compagny_edit", methods="GET|POST")
     */
    public function edit(Request $request, Compagny $compagny): Response
    {
        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compagny_index', ['id' => $compagny->getId()]);
        }

        return $this->render('compagny/edit.html.twig', [
            'compagny' => $compagny,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compagny_delete", methods="DELETE")
     */
    public function delete(Request $request, Compagny $compagny): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compagny->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compagny);
            $em->flush();
        }

        return $this->redirectToRoute('compagny_index');
    }
}
