<?php

namespace App\Controller;

use App\Repository\ToolsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToolsController extends AbstractController
{
    /**
     * @param string $slug
     * @return Response
     * @Route (
     *     "/code128/{slug}",
     *     name="app_tools_code128",
     *     host="{domain}",
     *     defaults={ "domain"="tools.maclews.eu" },
     *     requirements={ "domain"="tools.maclews.eu|www.tools.maclews.eu|tools.localhost" }
     *     )
     */
    public function code128(string $slug = ''): Response
    {
        if ($slug === 'print') {
            if (isset($_POST['cid']) && !empty($_POST['cid'])) {
                $cid = preg_replace('/[^A-Za-z0-9]+/m', ',', $_POST['cid']);
                $cid = explode(',', $cid);
                $data = ToolsRepository::code128table($cid);
                $count = $data[1];
                $table = $data[0];
            } else {
                $count = 'błąd';
                $table = '<td>NIE PRZESŁANO KODÓW KRESKOWYCH<br><br>WCIŚNIJ &lt;wstecz&gt; ABY WRÓCIĆ DO FORMULARZA</td>';
            }
            return $this->render('tools/print.code128.html.twig', [
                'pagetitle' => "Drukuj ({$count})",
                'datatable' => $table
            ]);
        }
        return $this->render('tools/code128.html.twig');
    }
}