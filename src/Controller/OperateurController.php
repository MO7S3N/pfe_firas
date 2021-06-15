<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */

class OperateurController extends AbstractController
{
    /**
     * @Route("/clients", name="clients")
     */
    public function index(UtilisateurRepository $repository): Response
    {
        $utilisateurs=$repository->findAll();
        return $this->render('operateur/index.html.twig', [
            'user'=>$utilisateurs
        ]);
    }
}
