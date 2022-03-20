<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SteamController extends AbstractController
{
    /**
     * @return Response
     * @Route (
     *     "/",
     *     name="app_steam_homepage",
     *     host="{domain}",
     *     defaults={ "domain"="steam.maclews.eu" },
     *     requirements={ "domain"="steam.maclews.eu|www.steam.maclews.eu|steam.localhost" }
     * )
     */
    public function homepage() : Response
    {
        return $this->render('steam.html.twig');
    }
}