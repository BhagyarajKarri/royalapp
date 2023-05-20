<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/books/add", name="books_add")
     */
    public function add(Request $request): Response
    {
        // Handle the form submission to add a new book
        // For example, using Symfony's form handling

        // Perform any necessary actions after adding the book
        $client = new Client([
                'verify' => false, // Disable SSL verification
            ]);

        $response = $client->get('https://candidate-testing.api.royal-apps.io/api/v2/authors', [
                                'headers' => [
                                    'Authorization' => $request->getSession()->get('access_token'),
                                ],
                        ]);
        $authorss = json_decode($response->getBody()->getContents(), true);
        $authors = $authorss['items'];

        return $this->render('book/add.html.twig',[
            'authors' => $authors,
        ]);
    }
}
