<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,ManagerRegistry $objectManager,UserPasswordEncoderInterface $encoder): Response
    {
        $utilisatteur = new Utilisateur();
        $form = $this->createForm(InscriptionType::class,$utilisatteur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {   $passwordCrypte=$encoder->encodePassword($utilisatteur,$utilisatteur->getPassword());
            $utilisatteur->setPassword($passwordCrypte);
            $utilisatteur->setRoles("ROLE_USER");
            $em=$objectManager->getManager();
            $em->persist($utilisatteur);
            $em->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('home/inscription.html.twig',[
            "form"=>$form->createView()

        ]);

    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $utils)
    {
        return $this->render('home/login.html.twig',[
            "lastusername"=>$utils->getLastUsername(),
            "error"=>$utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

}
