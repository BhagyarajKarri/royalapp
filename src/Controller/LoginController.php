<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(Request $request): Response
    {//echo "string";
        if ($request->isMethod('POST')) {
            //$client = new Client();
            $client = new Client([
                'verify' => false, // Disable SSL verification
            ]);

            $response = $client->post('https://candidate-testing.api.royal-apps.io/api/v2/token', [
                'json' => [
                    'email' => 'ahsoka.tano@royal-apps.io',
                    'password' => 'Kryze4President',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $accessToken = $responseData['token_key'];
            $firstName = $responseData['user']['first_name'];
            $lastName = $responseData['user']['last_name'];

            // Store the access token using any storage method you prefer
            // For example, you can use Symfony's session or a custom implementation
            // Here's an example using Symfony's session:
            $session = $request->getSession();
            $session->set('access_token', $accessToken);
            $session->set('first_name', $firstName);
            $session->set('last_name', $lastName);
               
                        // Redirect the user to a protected page or perform any other necessary actions
            return $this->redirectToRoute('protected_page');
        }

        return $this->render('login.html.twig');
    }

    /**
     * @Route("/protected-page", name="protected_page")
     */
    public function protectedPage(Request $request): Response
    {
        // Retrieve the access token from the storage method you used
        // For example, using Symfony's session:
         $session = $request->getSession();
        $accessToken = $session->get('access_token');

        // Make API requests using the access token as needed

        return $this->render('protected_page.html.twig', [
            'access_token' => $accessToken,
        ]);
    }

    /**
 * @Route("/logout", name="logout")
 */
public function logout(Request $request): Response
{
    // Clear the session and perform any necessary logout actions

    $request->getSession()->clear();

    // Redirect the user to the login page or any other desired page
    return $this->redirectToRoute('app_login');
}

}
