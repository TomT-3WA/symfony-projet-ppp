<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Track;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrackRepository;

class PepeController extends AbstractController
{
    /**
     * @Route("/pepe", name="pepe")
     */
    public function index(): Response
    {
        return $this->render('pepe/index.html.twig', [
            'controller_name' => 'PepeController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('pepe/home.html.twig');
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function tracks(TrackRepository $repo)
    {
        $tracks = $repo->findAll();

        return $this->render('pepe/tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    /**
     * @Route("/tracks/{id}", name="tracks_show")
     */
    public function tracks_show(Track $track, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setTrack($track);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('tracks_show', ['id' => $track->getId()]);
        }

        return $this->render('pepe/tracks-show.html.twig', [
            'track' => $track,
            'commentForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/pepe", name="pepe")
     */
    public function pepe()
    {
        return $this->render('pepe/pepe.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $form = $this->createFormBuilder()
            ->getForm();

        $form->handleRequest($request);

        dump($request);

        return $this->render('pepe/contact.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
