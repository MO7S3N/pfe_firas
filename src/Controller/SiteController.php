<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{

    /* ************* frooont ******************** */

    /**
     * @Route("/client/site", name="site")
     */
    public function index(SiteRepository $repository): Response
    {
        $sites = $repository->findAll();
        return $this->render('site/index.html.twig', [
            "sites"=>$sites
        ]);
    }


    /**
     * @Route("/client/site/{id}", name="site_id")
     */
    public function site_id(SiteRepository $repository,$id): Response
    {
        $sites = $repository->find($id);
        return $this->render('site/site_id.html.twig', [
            "sites"=>$sites
        ]);
    }

    /**
     * @Route("/client/pdf", name="devis")
     */
    public function devis(SiteRepository $repository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('site/pdf.html.twig', [
            'sites'=>$repository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("devis.pdf", [
            "Attachment" => false
        ]);

    }



    /* *************** back **************** */
    /**
     * @Route("/admin/site", name="siteback")
     */
    public function indexB(SiteRepository $repository): Response
    {
        $sites = $repository->findAll();
        return $this->render('site/indexback.html.twig', [
            "sites"=>$sites
        ]);
    }

    /**
     * @Route("/admin/ajoutersite", name="AjoutSite")
     * @Route("/admin/modifiersite/{id}", name="modifSite")
     */

    public function modifer_offre(Site $sites=null,Request $request,ManagerRegistry $objectManager): Response
    {
        if(!$sites)
        {
            $sites = new Site();
        }
        $form=$this->createForm(SiteType::class,$sites);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$objectManager->getManager();
            $em->persist($sites);
            $em->flush();
            $this->addFlash('success',"L'action a ete effectué");
            return $this->redirectToRoute('siteback');
        }
        return $this->render('site/ajoutersite.html.twig', [
            "sites"=>$sites,
            "form"=> $form->createView()
        ]);
    }

    /**
     * @Route("/admin/suppsite/{id}",name="supSite")
     */
    public function supprimerOffre(Site $site,Request $request,ManagerRegistry $objectManager)
    {
        if($this->isCsrfTokenValid("SUP".$site->getId(),$request->get("_token")))
        {
            $em=$objectManager->getManager();
            $em->remove($site);
            $em->flush();
            $this->addFlash('success',"L'action a ete effectué");
            return $this->redirectToRoute('siteback');
        }
    }

}
