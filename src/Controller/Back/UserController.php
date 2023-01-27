<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back-office/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_back_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter", name="app_back_user_add", methods={"GET", "POST"})
     */
    public function add(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            
            // hash the password (based on the security.yaml config for the $user class)
            // Le user permettra de retrouver la logique de hashagage correspondante dans le security.yaml
            // le user->getPassword() correspond au mdp en clair récupéré depuis l'input
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            // Une fois le tout hashé, on set le password avec sa version sécurisé
            // dd($hashedPassword, $user->getPassword());
            $user->setPassword($hashedPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="app_back_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher): Response
    {
        // Dans le createform, je peux préciser les options du formulaire, j'ai nottament modifier l'option edit que j'ai rajouté directement dans le formType
        $form = $this->createForm(UserType::class, $user,["edit" => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="app_back_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
