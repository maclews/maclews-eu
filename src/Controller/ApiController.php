<?php

namespace App\Controller;

use App\Repository\ApiRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @return Response
     * @Route(
     *     "/",
     *     name="app_api_index",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     */
    public function index() : Response
    {
        $json = new \stdClass();
        $json->status = "OK";
        return new JsonResponse($json);
    }

    /**
     * @return Response
     * @Route(
     *     "/ip",
     *     name="app_api_ip",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     */
    public function ip() : Response
    {
        return new Response($_SERVER['REMOTE_ADDR']);
    }

    /**
     * @return Response
     * @Route(
     *     "/code128",
     *     name="app_api_code128",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     */
    public function code128() : Response
    {
        return new RedirectResponse('https://tools.maclews.eu/code128', 301);
    }

    /**
     * @param string $slug
     * @param string $mode
     * @return Response
     * @Route(
     *     "/rmf/audio/{slug}/{mode}",
     *     name="app_api_rmfAudio",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     * @throws Exception
     */
    public function rmfAudio(string $slug, string $mode) : Response {
        $sources = [
            'rmffm' => 'https://www.rmfon.pl/stacje/flash_aac_5.xml.txt',
            'rmfmaxxx' => 'https://www.rmfon.pl/stacje/flash_aac_6.xml.txt',
            'rmfmaxxx-gda' => 'https://www.rmfon.pl/stacje/flash_aac_197.xml.txt'
        ];
        if (!array_key_exists($slug, $sources)) {
            return new Response('Undefined source');
        }
        $url = ApiRepository::rmfAudioXml($sources[$slug], $mode);
        return new RedirectResponse($url, 307);
    }

    /**
     * @param string $slug
     * @return Response
     * @Route(
     *     "/adblock/{slug}",
     *     name="app_api_adblock",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     */
    public function adblock(string $slug = '') : Response {
        $path = "/home/kmlnetu/domains/maclews.eu/txt/adblock/{$slug}.txt";
        if ($slug === '') {
            return new Response($this->adblockIndex());
        }
        if (\file_exists($path)) {
            return new Response(\file_get_contents($path), headers: [ 'content-type' => 'text/plain' ]);
        }
        return new Response('', 404);
    }

    private function adblockIndex(): string
    {
        $dir = "/home/kmlnetu/domains/maclews.eu/txt/adblock/";
        $files = \scandir($dir);
        unset($files[0], $files[1]);
        $html = '';
        foreach ($files as $f) {
            $f = \str_replace('.txt', '', $f);
            $html .= "<br><a href=\"./adblock/{$f}/\">{$f}</a><br>";
        }
        return $html;
    }

    /**
     * @param string $slug
     * @return Response
     * @Route(
     *     "/uuid/{slug}/{mode}",
     *     name="app_api_uuid",
     *     host="{domain}",
     *     defaults={ "domain"="api.maclews.eu" },
     *     requirements={ "domain"="api.maclews.eu|www.api.maclews.eu|api.localhost" }
     * )
     * @throws Exception
     */
    public function uuid4(string $slug = '1', string $mode = null) : Response
    {
        if ($slug === 'plain') {
            $guid = ApiRepository::guidv4();
            if ($mode === 'nodash') {
                $guid = str_ireplace('-', '', $guid);
            }
            return new Response($guid, 200, [ 'content-type' => 'text/plain' ]);
        }

        if (is_numeric($slug)) {
            $iMax = (int)$slug;
            $arr = [];
            for ($i = 0; $i < $iMax; $i++) {
                $guid = ApiRepository::guidv4();
                if ($mode === 'nodash') {
                    $guid = str_ireplace('-', '', $guid);
                }
                $arr[] = $guid;
            }
            $data = new \stdClass();
            $data->length = $iMax;
            $data->list = $arr;
        } else {
            $data = new \stdClass();
            $data->error = 'Invalid parameters passed';
        }
        return new JsonResponse($data);
    }
}
