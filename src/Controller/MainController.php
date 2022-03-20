<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return Response
     * @Route (
     *     "/",
     *     name="app_main_homepage",
     *     host="{domain}",
     *     defaults={ "domain"="maclews.eu" },
     *     requirements={ "domain"="maclews.eu|www.maclews.eu|localhost" }
     *     )
     */
    public function homepage() : Response
    {
        return $this->render('main.html.twig');
    }

    /**
     * @return Response
     * @Route (
     *     "/cheatsheet",
     *     name="app_main_cheatsheet",
     *     host="{domain}",
     *     defaults={ "domain"="maclews.eu" },
     *     requirements={ "domain"="maclews.eu|www.maclews.eu|localhost" }
     * )
     */
    public function cheatsheet() : Response
    {
        return $this->render('cheatsheet.html.twig');
    }

    /**
     * @return Response
     * @Route ("/lipsum",
     *     name="app_main_lipsum",
     *     host="{domain}",
     *     defaults={ "domain"="maclews.eu" },
     *     requirements={ "domain"="maclews.eu|www.maclews.eu|localhost" }
     * )
     */
    public function lipsum() : Response
    {
        return $this->render('lipsum.html.twig');
    }

}