<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Repository\UserRepository;
use App\Service\AuthenticationService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', 'app_authentication')]
class AuthenticationController extends AuthenticationService
{
	#[Route('', name: '_main', methods:['GET'])]
	public function index(): Response
	{
		return new Response('main');
	}

	#[Route('sign-in', name: '_sign_in', methods:['GET', 'POST'])]
	public function signInHttp(UserRepository $userRepository): Response
	{
		$method = 'getToken';
		$user = $this->getUser() ?? new User();
		if($this->isGranted(self::IS_AUTHENTICATED_FULLY)){
			return $this->redirectToRoute('app_dashboard_index', ['token' => $user->$method()]);
		}
		$signInForm = $this->createForm(SignInType::class, $user);
		if($this->getRequest()->getMethod() === Request::METHOD_POST){
			$user = $this->signIn($userRepository);
			return $this->redirectToRoute('app_dashboard_index', ['token' => $user->$method()]);
		}
		return $this->render('security/signIn.html.twig', compact('signInForm'));
	}

	#[Route('sign-out', name: '_sign_out', methods:['HEAD'])]
	public function signOutHttp(Security $security): Response
	{
		$this->signOut($security);
		return $this->redirectToRoute('app_authentication_main');
	}
}