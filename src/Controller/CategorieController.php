<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/client/categorie", name="categorie")
     */
    public function index(CategorieRepository $repository): Response
    {
        $categories = $repository->findAll();
        return $this->render('categorie/index.html.twig', [
            'categorie'=>$categories
        ]);
    }

    /**
     * @Route("/client/categorie/{id}", name="categoriesite")
     */
    public function categorie_site(CategorieRepository $repository,$id): Response
    {
        $categories = $repository->find($id);
        return $this->render('site/sitecategorie.html.twig', [
            'categories'=>$categories
        ]);
    }


}
