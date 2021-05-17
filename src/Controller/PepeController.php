<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Track;

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
    public function home() {
        return $this->render('pepe/home.html.twig');
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function tracks() {
        $repo = $this->getDoctrine()->getRepository(Track::class);

        $tracks = $repo->findAll();

        return $this->render('pepe/tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    /**
     * @Route("/tracks/{id}", name="tracks_show")
     */
    public function tracks_show($id) {
        $repo = $this->getDoctrine()->getRepository(Track::class);

        $track = $repo->find($id);

        return $this->render('pepe/tracks-show.html.twig', [
            'track' => $track
        ]);
    }
}
