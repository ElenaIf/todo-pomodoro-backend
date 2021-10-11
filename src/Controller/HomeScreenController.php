<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeScreenController extends AbstractController
{
public function home() {
//    return new Response('<h1>Hello world</h1>');
    // to get json response:
    return $this->json(['message' => 'hello world']);

    // $this refers to HomeScreenController. And json refers to AbstractController
}

    /**
     * @Route("/home2", methods={"GET","POST"}, name="home_screen")
     */
public function home2(Request $request): Response
{
    return $this->json([
        'new_routing' => 'with annotation instead of yaml file',
        'message' => $request->query->get('page')
        // above is the unified method to call the parameter from the url
    ]);
}

    /**
     * @Route("/error", methods={"GET"})
     */
public function error(){
//    This is why we gave the name to the route in home2 funtion
    return $this->redirectToRoute("home_screen");
}

    /**
     * @Route("/recipe/{id}", name="get_the_recipe", methods={"GET"})
     */
public function recipe($id) {
    return $this->json([
        'new_routing' => 'with annotation instead of yaml file',
        'message' => "this is id from the url: " . $id
        // above is the unified method to call the parameter from the url
    ]);
}

    /**
     * @Route("/recipe_with_request/{id}", methods={"GET"})
     */
    public function recipe1($id, Request $request) {
        return $this->json([
            'new_routing' => 'with annotation instead of yaml file',
            'message' => "this is id from the url: " . $id,
            // above is the unified method to call the parameter from the url
            'request' => $request->query->get('page')
        ]);
    }

    /**
     * @Route("/notes/all", name="get_all_notes", methods={"GET"})
     */
    public function getAllNotes(){
        // wee need this root path because otherwise we won't be able to access the json file here
        $rootPath = $this->getParameter('kernel.project_dir');
        $notes = file_get_contents($rootPath.'/resources/notes.json');
        $decodedNotes = json_decode($notes, true);
        return $this->json($decodedNotes);
    }

}

