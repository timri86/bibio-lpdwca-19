<?php

namespace App\FrontBundle\Controller;

use App\FrontBundle\Entity\Livre;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppFrontBundle:Default:index.html.twig');
    }
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('Users/index.html.twig', array(
            'users' => $users,
        ));
    }

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
        foreach ($users as $user){
            if($user==$user1){
                $livre->removeUser($user1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($livre);
                $em->flush();
                return $this->redirectToRoute('livre_index');
            }
        }
        $livre->addUser($user1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($livre);
        $em->flush();
        return $this->redirectToRoute('livre_index');
        
    }
}
