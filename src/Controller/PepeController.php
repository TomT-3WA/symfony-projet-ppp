<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Track;
use App\Repository\TrackRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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
    public function tracks_show(Track $track)
    {
        return $this->render('pepe/tracks-show.html.twig', [
            'track' => $track
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
