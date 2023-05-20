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
     * @Route("/books/new", name="book_new")
     */
    public function addBook(Request $request): Response
    {
        // Handle the form submission to add a new book
        // For example, using Symfony's form handling

        // Perform any necessary actions after adding the book

        return $this->redirectToRoute('author_list');
    }
}
