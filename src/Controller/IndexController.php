<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    #[Route('/', name: 'hello')]
    public function indexAction()
    {
        return $this->render('views/index.html.twig');
    }
}