<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Lien;
use App\Form\LienType;

class LienController extends Controller
{
    /**
     * @Route("/lien", name="lien")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Lien::class);
        $em = $this->getDoctrine()->getManager();
        $liens = $repository->findBy([], ['id' => 'DESC']);
        
        $lien = new Lien();

        $form = $this->createForm(LienType::class, $lien);
        $form->add('create', SubmitType::class, [
            'label' => 'Générer',
            'attr' => ['class' => 'btn btn-default pull-right']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // on ajoute un lien long si il n'est pas déjà présent dans la base sinon on ne fait rien...
            if(!$repository->findBy(['long' =>  $form["long"]->getData()])) {
                // on génére un lien court unique : on doit vérifier que le lien court n'a pas déjà été généré
                $hasard = uniqid();

                while($repository->findBy(['court' => $hasard])) {
                    $hasard = uniqid();
                }
                $lien->setCourt($hasard);
                $lien->setCompteur(0);
                $em->persist($lien);
                $em->flush();
            }
            return $this->redirectToRoute('index');
        }

        return $this->render('lien/index.html.twig', [
            'liens' => $liens,
            'form' => $form->createView()
        ]);
    }

    public function rediriger($court) {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Lien::class);
        $long = $repository->findOneBy(['court' => $court]);
        if (!$long) {
            throw $this->createNotFoundException("404 - Ce lien n'existe pas...");
        }
        // TODO : On incrémente le compteur
        $long->setCompteur($long->getCompteur() + 1);
        $em->persist($long);
        $em->flush();
        return $this->redirect($long->getLong());
    }
}
