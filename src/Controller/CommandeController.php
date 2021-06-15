<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/client/commande", name="commande")
     */
    public function index(CommandeRepository $repository): Response
    {
        $commandes=$repository->findAll();
        return $this->render('commande/index.html.twig', [
            "commande"=>$commandes
        ]);
    }

    /**
     * @Route("/client/commande", name="ajoutcommande")
     */
    public function ajoutcommande()
    {
      return $this->renderView('commande/ajoutcommande.html.twig');
    }
}
