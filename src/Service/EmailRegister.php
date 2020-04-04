<?php

// src/Service/Emailing.php
namespace App\Service;

class EmailRegister
{

    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        
    }

    // Méthode pour notifier par e-mail un administrateur
    
    /**
     * Send a email at the user with his Password the admin created / envoie un mail avec le mot de passe a l utilisateur que l admin vient de cree
     * @param string $mail
     * @param string&int $password
     */
    public function notifieParEmail($mail,$password)
    {
        
        $message = (new \Swift_Message('Test'))
            ->setFrom(['copets2020@gmail.com'])
            ->setTo([$mail])
            ->setBody(
                'Félicitations pour votre inscription à AdriverIo, voici votre Mot de passe : ' . $password . ' pensez à le changer lors de votre première connexion'
            );
        $this->mailer->send($message);
    }

    /**
     * Send a email at the userwhen he create a account with a confirmation link to activate his account / envoie un mail aal utlisateur quand il s incrit  avec un lien de confirmation d inscription
     * @param string $mail
     * @param int $token
     * @param string $name
     */
    public function InscriptionEmail($mail,$token,$name)
    {
        
        $message = (new \Swift_Message('Inscription'))
            ->setFrom(['copets2020@gmail.com'])
            ->setTo([$mail])
            ->setBody(
                'Bonjour ' . $name . ' Félicitations pour votre inscription à Co-pets, voici le lien pour confirmer votre inscription : http://localhost:8000/confirmation/' . $token . '/' . $mail . ' .'
            );
        $this->mailer->send($message);
    }

    /**
     *confirmation that the email at the tech team hase been recived/ confirmation que le mail envoyer a ete recu
     * @param \Swift_Mailer $mailer
     * @param string $mailUser
     */
    public function testSending($sendto,$sujet,$body,$data,$id)
    {
        $publicDirectory = 'missionement/Campagne_';
        $message = (new \Swift_Message($sujet))
            ->setFrom(['copets2020@gmail.com'])
            ->setTo([$sendto])
            ->setBody(
                $body
            )
            ->attach(\Swift_Attachment::fromPath($publicDirectory.$id.'_'.$data[0]->getDepot()->getDept().'_'.$data[0]->getHaulier()->getName().".csv", 'application/csv'));
            $this->mailer->send($message);
        
    }
}
