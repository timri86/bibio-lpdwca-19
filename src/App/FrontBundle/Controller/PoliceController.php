<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PoliceController extends Controller
{
    public function totoAction()
    {
      return $this->render('police.html.twig');
    }
}
