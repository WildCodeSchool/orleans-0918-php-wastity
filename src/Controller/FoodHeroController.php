<?php

namespace App\Controller;

use App\Entity\FoodHero;
use App\Form\FoodHeroType;
use App\Repository\FoodHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/new", name="food_hero_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $foodHero = new FoodHero();
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($foodHero);
            $em->flush();

            return $this->redirectToRoute('food_hero_index');
        }

        return $this->render('Visitor/FoodHero/new.html.twig', [
            'FoodHero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_hero_show", methods="GET")
     */
    public function show(FoodHero $foodHero): Response
    {
        return $this->render('Visitor/FoodHero/show.html.twig', ['FoodHero' => $foodHero]);
    }

    /**
     * @Route("/{id}/edit", name="food_hero_edit", methods="GET|POST")
     */
    public function edit(Request $request, FoodHero $foodHero): Response
    {
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('food_hero_index', ['id' => $foodHero->getId()]);
        }

        return $this->render('Visitor/FoodHero/edit.html.twig', [
            'FoodHero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_hero_delete", methods="DELETE")
     */
    public function delete(Request $request, FoodHero $foodHero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodHero->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($foodHero);
            $em->flush();
        }

        return $this->redirectToRoute('food_hero_index');
    }
}
