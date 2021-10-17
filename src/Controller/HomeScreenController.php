<?php


namespace App\Controller;


use App\Entity\Notes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeScreenController extends AbstractController
{
    public function home()
    {
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
    public function error()
    {
//    This is why we gave the name to the route in home2 funtion
        return $this->redirectToRoute("home_screen");
    }

    /**
     * @Route("/recipe/{id}", name="get_the_recipe", methods={"GET"})
     */
    public function recipe($id)
    {
        return $this->json([
            'new_routing' => 'with annotation instead of yaml file',
            'message' => "this is id from the url: " . $id
            // above is the unified method to call the parameter from the url
        ]);
    }

    /**
     * @Route("/recipe_with_request/{id}", methods={"GET"})
     */
    public function recipe1($id, Request $request)
    {
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
    public function getAllNotes()
    {
        $notes = $this->getDoctrine()->getRepository(Notes::class)->findAll();

        $response = [];

        foreach ($notes as $note) {
            $response[] = array(
                'id'=> $note->getId(),
                'title' => $note->getTitle(),
                'done' => $note->getDone(),
                'timeSpent' => $note->getTimeSpent(),
                'color' => $note->getColor(),
                'userid' => $note->getUserid(),
                'hashtag' => $note->getHashtag()
            );
        }
        return $this->json($response);

        // All this was for json file
        // wee need this root path because otherwise we won't be able to access the json file here
//        $rootPath = $this->getParameter('kernel.project_dir');
//        $notes = file_get_contents($rootPath.'/resources/notes.json');
//        $decodedNotes = json_decode($notes, true);
//        return $this->json($decodedNotes);
    }


    /**
     * @Route("/notes/find/{userid}", name="get_all_notes_for_user", methods={"GET"})
     */
    public function getAllUserNotes($userid)
    {
        $notes = $this->getDoctrine()->getRepository(Notes::class)->findBy( ['userid' => $userid],
            ['id' => 'DESC']);



        if(!$notes) {
            throw $this->createNotFoundException(
                "No notes for this user"
            );
        } else {
            $response = [];
            foreach ($notes as $note) {
                $response[] = array(
                    'id'=> $note->getId(),
                    'title' => $note->getTitle(),
                    'done' => $note->getDone(),
                    'timeSpent' => $note->getTimeSpent(),
                    'color' => $note->getColor(),
                    'userid' => $note->getUserid(),
                    'hashtag' => $note->getHashtag()
                );
            }
            return $this->json($response);
        }

    }


    /**
     * @Route("/notes/add", name="add_new_note", methods={"POST"})
     */
    public function addNote(Request $request)
    {
//  we need not only create those items for the database, but we need to save them into database also. For that we use entityManager
//  we call doctrine from the abstract class

        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(),true);

        $newNote = new Notes();
        $newNote->setTitle($data["title"]);
        $newNote->setDone($data["done"]);
        $newNote->setTimeSpent($data["timeSpent"]);
        $newNote->setColor($data["color"]);
        $newNote->setUserid($data["userid"]);
        $newNote->setHashtag($data["hashtag"]);

        // put everything together before saving into database
        $entityManager->persist($newNote);
        //        Perform saving into database
        $entityManager->flush();

        return new Response('New note was added: ' . $newNote->getId() . " - " . $newNote->getTitle());
    }

    /**
     * @Route("/notes/delete/{id}", name="delete_note", methods={"DELETE"})
     */
    public function deleteNote($id){
        $note = $this->getDoctrine()->getRepository(Notes::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();

        if(!$note){
            throw $this->createNotFoundException(
                "No note was found with the ID of " . $id
            );
        } else {
            $entityManager->remove($note);
            $entityManager->flush();
            return $this->json([
                'message' => 'Deleted note with id ' . $id
            ]);
        }
    }

    /**
     * @Route("/notes/edit/{id}", name="edit_note", methods={"PUT"})
     */
    public function editNote($id, Request $request) {
        $note = $this->getDoctrine()->getRepository(Notes::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(),true);

        if(!$note){
            throw $this->createNotFoundException(
                "No note was found with the ID of " . $id
            );
        } else {
            $note->setTitle($data["title"]);
            $note->setDone($data["done"]);
            $note->setTimeSpent($data["timeSpent"]);
            $note->setHashtag($data["hashtag"]);
//            $note->setColor($data["color"]);

            $entityManager->flush();

            return $this->json([
                'message' => 'Edited note with id of ' . $id
            ]);
        }

    }




}

