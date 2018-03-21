<?php
    namespace App\Controller;

    use Miraizou\Helper\UrlParser;
    use Miraizou\Helper\UrlParserException;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class Home extends Controller
    {
        /**
         * Show a form to input an URL or list of URLs
         *
         * @Route("/", name="app_home")
         * @Method({"GET"})
         *
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function showForm() : Response
        {
            return $this->render('home.html.twig');
        }

        /**
         * For each given URL, show a table with all its components
         *
         * @Route("/", name="app_result")
         * @Method({"POST"})
         *
         * @param Request $request
         *
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function showResult(Request $request) : Response
        {
            $formUrls = $request->request->get('urls', '');
            if (false === empty($formUrls)) {
                $errors = $urls = [];
                foreach(preg_split('/\R+/', $formUrls, -1, PREG_SPLIT_NO_EMPTY) as $url) {
                    try {
                        $parser = new UrlParser($url);

                        $urls[] = [
                          'url' => $url,
                          'components' => [
                            ['name' => 'Scheme', 'value' => $parser->getScheme()],
                            ['name' => 'Username', 'value' => $parser->getUsername()],
                            ['name' => 'Password', 'value' => $parser->getPassword()],
                            ['name' => 'Hostname', 'value' => $parser->getHost()],
                            ['name' => 'Port', 'value' => $parser->getPort()],
                            ['name' => 'Path', 'value' => $parser->getPath()],
                            ['name' => 'Query String', 'value' => $parser->getQuery()],
                            ['name' => 'Fragment', 'value' => $parser->getFragment()],
                          ]
                        ];
                    } catch (UrlParserException $e) {
                        $errors[] = $e->getMessage();
                    }
                }

                return $this->render('result.html.twig', ['errors' => $errors, 'urls' => $urls]);
            }

            return $this->render('home.html.twig', [
              'errors' => ['Missing URLs'],
            ]);
        }
    }
