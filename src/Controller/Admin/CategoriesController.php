<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoriesType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories", name="admin_categories_")
 * @package App\Controller\Admin
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $catsRepo): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $catsRepo->findAll()
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutCategorie(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category;

        $form = $this->createForm(CategoriesType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'La track a bien été ajoutée');

            return $this->redirectToRoute('admin_categories_home');
        }
        return $this->render('admin/categories/ajout.html.twig', [
            'formAjoutCat' => $form->createView(),
            'id' => $category->getId()
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifCategorie(Category $category, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CategoriesType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'La track a bien été modifiée');

            return $this->redirectToRoute('admin_categories_home');
        }
        return $this->render('admin/categories/modif.html.twig', [
            'formModifCat' => $form->createView(),
            'id' => $category->getId()
        ]);
    }
}
