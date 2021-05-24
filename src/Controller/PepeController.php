<?php

namespace App\Controller;

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
use App\Form\CreateType;
use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     * @Route("/tracks/new", name="track_create")
     * @Route("/tracks/{id}/edit", name="track_edit")
     */
    public function form(Track $track = null, Request $request, EntityManagerInterface $manager)
    {
        if (!$track) {
            $track = new Track();
        }

        $form = $this->createForm(CreateType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$track->getId()) {
                $track->setCreatedAt(new \DateTime());
            }
            // Upload du fichier de musique
            $uploadSong = $track->getFile();
            $uploadSongName = md5(uniqid()) . '.' . $uploadSong->guessExtension();
            $uploadSong->move($this->getParameter('upload_directory'), $uploadSongName);
            $track->setFile($uploadSongName);
            // Upload du fichier image
            $uploadImage = $track->getImage();
            $uploadImageName = md5(uniqid()) . '.' . $uploadImage->guessExtension();
            $uploadImage->move($this->getParameter('upload_directory'), $uploadImageName);
            $track->setImage($uploadImageName);
            $manager->persist($track);
            $manager->flush();
            $this->addFlash('success', 'La track a bien été modifiée');

            return $this->redirectToRoute('tracks_show', ['id' => $track->getId()]);
        }

        return $this->render('pepe/create.html.twig', [
            'track' => $track,
            'createForm' => $form->createView(),
            'editMode' => $track->getId() !== null
        ]);
    }

    /**
     * @Route("/tracks/{id}/delete", name="track_delete")
     * @param Track $track
     * @return RedirectResponse
     */
    public function delete(Track $track): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($track);
        $em->flush();

        return $this->redirectToRoute("tracks");
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
