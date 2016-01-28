<?php

namespace AppBundle\Controller;

use GuzzleHttp\Psr7\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/last-release", name="last_release")
     */
    public function releaseAction()
    {
        $client = $this->get('csa_guzzle.client.github_api');
        /** @var Response $response */
        $response = $client->get('/repos/sroze/Tolerance/releases/latest');
        $contents = $response->getBody()->getContents();
        $release = json_decode($contents, true);

        return $this->render('default/release.html.twig', [
            'release' => $release,
        ]);
    }
}
