<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Cours;
use App\Entity\Horaires;
use App\Entity\Reservations;

use App\Entity\Salles;
use App\Form\ClientsType;
use App\Repository\HorairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {

        return $this->render('default/index.html.twig');

    }

    /**
     * @Route("/salles", name="salles")
     */
    public function Salles()
    {
        $salles = $this->getDoctrine()->getRepository((Salles::class))->findAll();

        return $this->render('default/Salles.html.twig', ['Salles' => $salles]);
    }

    /**
     * @Route("/salle/{id}", name="salle")
     */
    public function Salle($id)
    {
        $salle = $this->getDoctrine()->getRepository((Salles::class))->find($id);

        return $this->render('default/Salle.html.twig', ['salle' => $salle]);
    }

    /**
     * @Route("/planning/{id}", name="planning")
     */
    public function planning($id)
    {

        $planningSalle = $this->getDoctrine()->getRepository((Horaires::class))->findBy(['idsalle' => $id]);
        $idSalle=$planningSalle[0]->getIdSalle()->getId();

        return $this->render('default/Planning.html.twig', ['planning' => $planningSalle, 'idsalle'=>$idSalle]);
    }



    /**
     * @Route("/ajoutClient", name="AjoutClient")
     */
    public function ajoutClient(Request $request, UserPasswordEncoderInterface $encoder) {
        $client = new Clients();

        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {





            $em = $this->getDoctrine()->getManager();

            $hash= $encoder->encodePassword($client, $client->getPassword());
            $client->setPassword($hash);

            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('CompteCree');
        }

        return $this->render('default/client_ajout.html.twig', ['form' => $form->createView()]);
    }





    /**
     * @Route("/Comptecree", name="CompteCree")
     */
    public function CompteCree(){
        return $this->render('default/CompteCree.html.twig');
    }


    /**
     * @Route("/login", name = "login")
     */
    public function Login(UserPasswordEncoderInterface $encoder){


        return $this->render( 'default/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name = "deconnexion")
     */
    public function logout() {}



    /**
     * @Route("/Reserver/{id}", name="reserver")
     */
    public function Reserver($id, SessionInterface $session, HorairesRepository $horairesRepository)
    {

        $panier = $session->get('panier', []);


            if (!empty($panier[$id])) {
                /***récuperer l'id de la salle ****/
                $horaire = $this->getDoctrine()->getRepository((Horaires::class))->findOneBy(['id' => $id]);
                $Salle = $this->getDoctrine()->getRepository((Salles::class))->findOneBy(['id' => $horaire->getIdSalle()]);
                $idSalle = $Salle->getId();
                return $this->render('default/error2timesTheSame.html.twig', ['id' => $idSalle]);
            } else {
                /***recuperer les heure présentes dans les horaires déjà enregistré**/



                    $horaireEnre = $this->getDoctrine()->getRepository((Horaires::class))->findOneBy(['id' => $id]);
                    $Salle = $this->getDoctrine()->getRepository((Salles::class))->findOneBy(['id' => $horaireEnre->getIdSalle()]);
                    $idSalle = $Salle->getId();
                    if (!empty($panier)) {
                        foreach ($panier as $idp => $q) {
                            $horaireCurrent = $horairesRepository->find($idp);
                            if ($horaireCurrent->getHeure() == $horaireEnre->getHeure() && $horaireCurrent->getJour() == $horaireEnre->getJour()) {

                                return $this->render('default/error2times.html.twig', ['id' => $idSalle, 'horaire' => $horaireCurrent, 'horaire2' => $horaireEnre]);

                            }

                        }
                    }


                        $panier[$id] = 1;
                        $session->set('panier', $panier);

                        return $this->redirectToRoute('panier');



            }




    }

    /**
     *@Route("/panier", name="panier")
     */
    public function panier( SessionInterface $session, HorairesRepository $horairesRepository)
    {
        if (!empty($session->get('panier', []))) {


            $reservations = $session->get('panier', []);
            $panierWithdata = [];
            foreach ($reservations as $id => $q) {
                $horaireCurrennnt = $horairesRepository->find($id);
                $panierWithdata [] = [
                    'reservation' => $horaireCurrennnt
                ];
            }

            /***Récupérer tout les id des horaires du panier, il nous servirons pour les enregistrer dans la base de donnés si enregistrement du panier****/
            $reservations = $session->get('panier', []);
            $toutlesId = [];
            foreach ($reservations as $id => $q) {
                $horaireCurrennnt = $horairesRepository->find($id);
                $toutlesId [] = [
                    $horaireCurrennnt->getId()
                ];
            }

        }else{
            $panierWithdata=[];
            $toutlesId=[];
        }







        return $this->render('default/panier.html.twig', [ 'reservations' => $panierWithdata, 'toutlesId'=>$toutlesId]);

    }


    /**
     * @Route("/panier/remove/{id}", name="remove")
     */
    public function remove($id, SessionInterface $session){

        $panier=$session->get('panier');

        if (!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }







    /**
     * @Route("/enregistrePanier/{email}", name="enregistrePanier")
     */
    public function enregistrePanier($email, SessionInterface $session, HorairesRepository $horairesRepository)
    {

        /***Récupérer tout les id des horaires du panier, il nous servirons pour les enregistrer dans la base de donnés si enregistrement du panier****/
        $reservations=$session->get('panier', []);
        $toutlesId= [];
        foreach($reservations as $id => $q)
        {
            $horaireCurrennnt= $horairesRepository->find($id);
            $toutlesId []  = [
                $horaireCurrennnt->getId()
            ];
        }
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($toutlesId as $id){
            foreach ($id as $i){
                $product = new Reservations();
                $product->setEmailClient($email);
                $product->setIdHoraire($i);

                $entityManager->persist($product);

                $entityManager->flush();

            }


        }

        $session->set('panier',[]);

        return $this->redirectToRoute('profil', array('email'=>$email));

    }
    /**
     * @Route("/profil/{email}", name="profil")
     */
    public function profil( $email, SessionInterface $session)
    {

        $Reserv = $this->getDoctrine()->getRepository((Reservations::class))->findBy(array("EmailClient"=> $email));
        $Horaires= [];
        foreach ($Reserv as $r){



            $Horaires []=$this->getDoctrine()->getRepository((Horaires::class))->findBy(array("id"=>$r->getIdHoraire()));
        }

        return $this->render('default/profil.html.twig', [ 'reservationsEnr' => $Horaires, 'Email'=>$email]);



    }
    /**
     * @Route("/profil/remove/{idH}/{idC}", name="ProfilRemove")
     */
    public function ProfilRemove($idH, $idC, SessionInterface $session){


        $reserv= $this->getDoctrine()->getRepository((Reservations::class))->findOneBy(array('idHoraire'=>$idH, 'EmailClient'=>$idC));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reserv);
        $entityManager->flush();


        return $this->redirectToRoute('profil', array('email'=>$idC));
    }






    



}

