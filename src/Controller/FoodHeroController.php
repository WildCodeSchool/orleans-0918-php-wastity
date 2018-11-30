<?php

namespace App\Controller;

use App\Entity\FoodHero;
use App\Form\FoodHeroType;
use App\Repository\FoodHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/foodhero")
 */
class FoodHeroController extends AbstractController
{
    /**
     * @Route("/", name="foodhero_index", methods="GET")
     */
    public function index(FoodHeroRepository $foodHeroRepository): Response
    {
        return $this->render('Visitor/FoodHero/index.html.twig', ['food_heroes' => $foodHeroRepository->findAll()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="foodhero_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $foodHero = new FoodHero();
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);
        
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $foodHero->setUser($user);
            $em->persist($foodHero);
            $em->flush();

            return $this->redirectToRoute('foodhero_index');
        }

        return $this->render('Visitor/FoodHero/new.html.twig', [
            'FoodHero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_show", methods="GET")
     */
    public function show(FoodHero $foodHero): Response
    {
        return $this->render('Visitor/FoodHero/show.html.twig', ['FoodHero' => $foodHero]);
    }

    /**
     * @Route("/{id}/edit", name="foodhero_edit", methods="GET|POST")
     */
    public function edit(Request $request, FoodHero $foodHero): Response
    {
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('foodhero_index', ['id' => $foodHero->getId()]);
        }

        return $this->render('Visitor/FoodHero/edit.html.twig', [
            'FoodHero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_delete", methods="DELETE")
     */
    public function delete(Request $request, FoodHero $foodHero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodHero->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($foodHero);
            $em->flush();
        }

        return $this->redirectToRoute('foodhero_index');
    }
}
