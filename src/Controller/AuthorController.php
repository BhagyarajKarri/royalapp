<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authors", name="author_list")
     */
    public function listAuthors(Request $request): Response
    {
        //$client = new Client();
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
//print_r($authors);
//dump($authors);
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/authors/{id}", name="author_view")
     */
    public function viewAuthor(Request $request, $id): Response
    {
        //$client = new Client();
            $client = new Client([
                'verify' => false, // Disable SSL verification
            ]);
        $response = $client->get('https://candidate-testing.api.royal-apps.io/api/v2/authors/' . $id, [
                                'headers' => [
                                    'Authorization' => $request->getSession()->get('access_token'),
                                ],
                        ]);
        $author = json_decode($response->getBody()->getContents(), true);

        return $this->render('author/view.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/authors/{id}/delete", name="author_delete")
     */
    public function deleteAuthor(Request $request, $id): Response
    {
        // Check if the author has related books
        $client = new Client([
                'verify' => false, // Disable SSL verification
            ]);
        $response = $client->get('https://candidate-testing.api.royal-apps.io/api/v2/authors/' . $id, [
                                'headers' => [
                                    'Authorization' => $request->getSession()->get('access_token'),
                                ],
                        ]);
        $responses = json_decode($response->getBody()->getContents(), true);
        $books = $responses['books'];

        if (empty($books)) {
            // Delete the author if there are no related books
            $response = $client->delete('https://candidate-testing.api.royal-apps.io/api/v2/authors/' . $id, [
                                'headers' => [
                                    'Authorization' => $request->getSession()->get('access_token'),
                                ],
                        ]);

            // Perform any necessary actions after deletion

            return $this->redirectToRoute('author_list');
        } else {
            // Handle the case where the author has related books
            // For example, display an error message or redirect with a flash message

            return $this->redirectToRoute('author_view', ['id' => $id]);
        }
    }

    /**
     * @Route("/authors/new", name="author_new")
     */
    public function addAuthor(Request $request): Response
    {
        // Handle the form submission to add a new author
        // For example, using Symfony's form handling

        // Perform any necessary actions after adding the author

        return $this->redirectToRoute('author_list');
    }
}
