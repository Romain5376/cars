<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $repository, PaginatorInterface $paginatorInterface, Request $request)
    {
        $rechercheVoiture = new RechercheVoiture();

        $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
        $form->handleRequest($request);

        $voitures = $paginatorInterface->paginate(
            $repository->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig',[
            "voitures" => $voitures,
            "form" => $form->createView(),
            "admin" => true
        ]);
    }

    /**
     * @Route("/admin/creation", name="ajoutVoiture")
     * @Route("/admin/{id}", name="modifVoiture", methods="GET|POST")
     */
    public function ajoutEtModification(Voiture $voiture = null, Request $request, EntityManagerInterface $entityManager){

        if(!$voiture){
            $voiture = new Voiture();
        }

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $modif = $voiture->getId() !== null;
            $entityManager->persist($voiture);
            $entityManager->flush();
            $this->addFlash('success',($modif) ? "La modification à été effectuée" : "L'ajout à été effectué");
            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/ajoutEtModification.html.twig',[
            "voiture" => $voiture,
            "form" => $form->createView(),
            "isModification" => $voiture->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/{id}", name="suppressionVoiture", methods="SUP")
     */
    public function suppression(Voiture $voiture, Request $request, EntityManagerInterface $entityManager){
        if($this->isCsrfTokenValid("SUP" . $voiture->getId(),$request->get("_token"))){
            $entityManager->remove($voiture);
            $entityManager->flush();
            $this->addFlash('success',"La suppression à été effectuée");
            return $this->redirectToRoute("admin");

        }

    }
    
}
