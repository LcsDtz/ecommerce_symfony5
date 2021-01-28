<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanelController extends AbstractController
{
    protected $productRepository;
    protected $categoryRepository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/panel", name="admin_panel")
     * IsGranted("ROLE_ADMIN")
     */
    public function home()
    {
        return $this->render('admin/panel.html.twig');
    }

    /**
     * @Route("/admin/panel/category", name="category_panel")
     * IsGranted("ROLE_ADMIN")
     */
    public function showCategory()
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('admin/category.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/panel/product", name="product_panel")
     * IsGranted("ROLE_ADMIN")
     */
    public function showProduct()
    {
        $products = $this->productRepository->findAll();

        return $this->render('admin/product.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/admin/panel/user", name="user_panel")
     * IsGranted("ROLE_ADMIN")
     */
    public function showUser()
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }
}
