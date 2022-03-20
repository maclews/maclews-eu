<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoController extends AbstractController
{
    /**
     * @return Response
     * @Route(
     *     "/",
     *     name="app_go_index",
     *     host="{domain}",
     *     defaults={ "domain"="go.maclews.eu" },
     *     requirements={ "domain"="go.maclews.eu|www.go.maclews.eu|go.localhost" }
     * )
     */
    public function index() : Response
    {
        return new Response('', 403);
    }

    /**
     * @return Response
     * @Route(
     *     "/pmsa/{slug}",
     *     name="app_go_pmsa",
     *     host="{domain}",
     *     defaults={ "domain"="go.maclews.eu" },
     *     requirements={ "domain"="go.maclews.eu|www.go.maclews.eu|go.localhost" }
     * )
     */
    public function pmsa($slug) : Response
    {
        if ($slug === 'xprimer') {
            $i = $_SERVER['REMOTE_ADDR'];
            if ($i === '91.212.242.200' || $i === '91.212.242.201') {
                return new RedirectResponse("http://10.69.5.13:8080/pmsa/auth/login?username=malewandowski", 307);
            }
            return new Response('', 403);
        }
        return new Response('', 404);
    }

    /**
     * @param $slug
     * @return Response
     * @Route(
     *     "/qrz/{slug}",
     *     name="app_go_qrz",
     *     host="{domain}",
     *     defaults={ "domain"="go.maclews.eu" },
     *     requirements={ "domain"="go.maclews.eu|www.go.maclews.eu|go.localhost" }
     * )
     */
    public function qrz($slug) : Response
    {
        if ($slug === 'solar') {
            $t = time();
            return new RedirectResponse("https://www.hamqsl.com/solar101vhfpic.php?t={$t}");
        }
        return new Response('', 404);
    }
}
