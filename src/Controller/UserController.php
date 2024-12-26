<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/user', name: 'app_user')]
class UserController extends UserService
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct($entityManagerInterface);
    }

    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        $userForm = $this->createForm(UserType::class, new User());
        $parameters = array_merge(
            $this->getModuleAndTableProperties(...$this->getProperties()),
            compact('userForm')
        );
        return $this->render('user/index.html.twig', $parameters);
    }

    #[Route('/all', name: '_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        return $this->json($this->all());
    }

    #[Route('/save/{id?}', name: '_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveAction(ValidatorInterface $validatorInterface, ?User $user = null): JsonResponse
    {
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            if(!$user instanceof User){
                throw new BadRequestException("Undefined category.", 400);
            }
            $this->delete($user);
        }else{
            $this->save($validatorInterface, UserType::class, $user);
        }
        return $this->json($this->getRes(), $this->getCode());
    }
}
