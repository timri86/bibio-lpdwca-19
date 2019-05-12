<?php

namespace App\FrontBundle\Controller;

use App\FrontBundle\Entity\Livre;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('default/accueil.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('Users/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function edituserAction(User $user)
    {
        if ($user->isEnabled()) {
            $user->setEnabled(0);
        }
        else{
            $user->setEnabled(1);
        }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        return $this->redirectToRoute('app_front_users');
    }
    public function empruntAction(Livre $livre)
    {
        $user1=$this->getUser();
        $users=$livre->getUsers();
        $nbre=$livre->getNbre();
        $dispo=$livre->getDisponibilite();
        foreach ($users as $user){
            if($user==$user1){
                $livre->removeUser($user1);
                if($nbre==0){
                    $livre->setDisponibilite(1);
                }
                $nbre=$nbre+1;

                $em = $this->getDoctrine()->getManager();
                $livre->setNbre($nbre);
                $em->persist($livre);
                $em->flush();
                return $this->redirectToRoute('livre_index');
            }
        }

        $livre->addUser($user1);

        $nbre=$nbre-1;
        if($nbre==0){
            $livre->setDisponibilite(0);
        }
        $em = $this->getDoctrine()->getManager();
        $livre->setNbre($nbre);
        $em->persist($livre);
        $em->flush();
        return $this->redirectToRoute('livre_index');
    }
}
