<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;
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

    /**
     * @Route("/embedded/{service}/{page}", name="embedded")
     */
    public function embeddedAction($service, $page)
    {
        $url = sprintf('http://%s/app_dev.php/%s', $service, $page);
        $response = $this->getClient('internal')->get($url);

        return $this->render('default/embedded.html.twig', [
            'service' => $service,
            'page' => $page,
            'contents' => $response->getBody()->getContents(),
        ]);
    }

    /**
     * @param string $name
     *
     * @return Client
     */
    private function getClient($name)
    {
        return $this->get('csa_guzzle.client.'.$name);
    }
}
