<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProsuctType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'main_homepage')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();
        return $this->render('main/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
//    #[Route('/product-add', name: 'product_add')]
//    public function productAdd(): Response
//    {
//        $product = new Product();
//        $product->setDescription('descr it is brong');
//        $product->setTitle('title' . rand(1, 100));
//        $product->setPrice(123);
//        $product->setQuantity(2);
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($product);
//        $entityManager->flush();
//        return $this->redirectToRoute('main_homepage');
//    }

    #[Route('/edit-product/{id}', name: 'edit_product', requirements: ['id' => "\d+"], methods: ['GET', 'POST'])]
    #[Route('/add-product', name: 'add_product', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, int $id = null): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($id){
            $product = $entityManager->getRepository(Product::class)->find($id);
        }
        else{
        $product = new Product();
        $product->setDescription('descry it is bring');
        $product->setTitle('title' . rand(1, 100));
        $product->setPrice(123);
        $product->setQuantity(2);
        $entityManager->persist($product);
        $entityManager->flush();
        }
        $form = $this->createForm(EditProsuctType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('edit_product', ['id' => $product->getId()]);
        }
        return $this->render('main/default/edit_product.html.twig', ['form'=>$form->createView()]);
    }
}
