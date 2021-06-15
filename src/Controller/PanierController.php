<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/client/panier", name="panier")
     */
    public function index(SessionInterface $session,SiteRepository $repository)
    {
      $panier = $session->get("panier",[]);
      //on fabrique les donnees
      $datapanier=[];
      $total=0;
      foreach ($panier as $id=>$quantite)
      {
        $site=$repository->find($id);
        $datapanier[] =[
            "sites"=>$site,
            "quantite"=>$quantite
        ];
        $total+=$site->getPrix()*$quantite;
      }
      return $this->render("panier/index.html.twig",compact("datapanier","total"));
    }

    /**
     * @Route("/client/ajouterpanier/{id}", name="ajoutpanier")
     */
    public function ajouterpanier(Site $sites, SessionInterface $session)
    {
        $panier = $session->get("panier",[]);
        $id=$sites->getId();
        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }
        else
        {
            $panier[$id]= 1 ;
        }
        $session->set("panier",$panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/client/supprimer/{id}", name="supprimerpanier_elemnt")
     */
    public function supprimerpanier(Site $sites, SessionInterface $session)
    {
        $panier = $session->get("panier",[]);
        $id=$sites->getId();
        if(!empty($panier[$id]))
        {
            if($panier[$id]>1)
            {
             $panier[$id]--;
            }
            else
            {
                unset($panier[$id]);
            }
        }

        $session->set("panier",$panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/client/supprimerpanier/{id}", name="supprimerpanier")
     */
    public function supprimerpanierligne(Site $sites, SessionInterface $session)
    {
        $panier = $session->get("panier",[]);
        $id=$sites->getId();

        if(!empty($panier[$id]))
        {
                unset($panier[$id]);

        }

        $session->set("panier",$panier);

        return $this->redirectToRoute("panier");
    }

}
