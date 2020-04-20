<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\EmailRegister;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailRegister $emailRegister): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, array(
            'inscription' => true
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $time = new \DateTime('now');
            $user->setRegisterAt($time);
            $user->setStatut(0);
            $user->setToken($this->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $token = $user->getToken();
            $email = $user->getEmail();
            $name = $user->getNom();
            $emailRegister->InscriptionEmail($email,$token,$name);
            $this->addFlash('success', 'Votre inscription a été validée, vous allez recevoir un email de confirmation pour activer votre compte');
            return $this->redirectToRoute('connexion');
        }

        return $this->render('register/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * Envoie un email de confirmation au User qui vient de s'inscrire
     * @param EmailRegister $emailRegister
     * @param string $email
     * @return RedirectResponse AbstractControllerds
     * @Route("/envoyerconfirmation/{email}", name="envoyerconfirmation")
     */
    public function renvoiConfirmation(EmailRegister $emailRegister,$email): Response
    {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            $token = $user->getToken();
            $mail = $user->getEmail();
            $name = $user->getNom();

            $emailRegister->InscriptionEmail($mail,$token,$name);
           
            return $this->redirectToRoute('accueil');
    }



    /**
     * Quand l'utilisateur clique sur le lien reçu par email il est renvoyé à cette page puis le token du lien est comparé à celui de la table User puis change le statut de l'utilisateur
     * @param int $token
     * @param string $email
     * @return RedirectResponse AbstractControllerds
     * @Route("/confirmation/{token}/{email}", name="confirmation_compte")
     */
    public function confirmAccount($token, $email): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        $tokenExist = $user->getToken();

        if($token === $tokenExist) {
           
           $user->setToken(null);
           $user->setStatut(1);
           $user->setRoles(['ROLE_USER']);
           $em->persist($user);
           $em->flush();

           if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('deconnexion');
         }
        } 
        return $this->redirectToRoute('connexion');
    }
 



    /**
     * Génère un token entre 1 et 99999
     * @return int $random
     */
    private function generateToken()
    {
        $random = random_int(1, 99999);

        return $random;
    }


        /**
     *  @param Request $request
     * @return JsonResponse $data
     * @Route("/ajaxDepartement", name="ajaxDepartement")
     */
    public function ajaxDepartement(Request $request, DepartementRepository $repoDepartement): Response
    {
        $region = $request->request->get('region');
        dump($region);
        $departementByRegion = $repoDepartement->findDpt($region); dump($departementByRegion);

        $data = $this->jsonForDepartement($departementByRegion);
        return new JsonResponse($data);
     
    }


    


    /**
     * Fonction que permet de créer un array des départements 
     * @param array $dpt
     * @return array $data
     */
    public function jsonForDepartement ($dpt)
    {
        $data = array ();
        for ($i = 0; $i < count($dpt); $i++)
        {
           $dpt[$i] = array("nom"=>$dpt[$i]->getNom(),"id"=>$dpt[$i]->getId());
           
        }
        array_push($data, $dpt);
        return $data; dump($data);
    }




}
