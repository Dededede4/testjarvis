<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\User;
use App\Form\UserType;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @RouteResource("User", pluralize=false)
 */
class UserController extends FOSRestController
{
	protected $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	protected function updateUserWithRequest(Request $request, User $user)
	{
		$form = $this->createForm(UserType::class, $user);
		$data = json_decode($request->getContent(), true);
		$form->submit($data);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			return $this->handleView($this->view($user, Response::HTTP_CREATED));
		}
		return $this->handleView($this->view(['status' => 'error'], Response::HTTP_FORBIDDEN));
	}

	public function postUserAction(Request $request)
	{
		$user = new User();
		$user->setCreationdate(new \DateTime());
		$user->setUpdatedate(new \DateTime());
		return $this->updateUserWithRequest($request, $user);
	}

	public function putUserAction(Request $request, User $user)
    {
    	$user->setUpdatedate(new \DateTime());
    	return $this->updateUserWithRequest($request, $user);
    }


	// L’API doit comprendre un listener utilisant Logger pour écrire dans les logs chaque modification d’un enregistrement.

	public function getUsersAction()
    {
    	return $this->handleView($this->view($this->em->getRepository(User::class)->findAll()));
    }


    public function getUserAction(User $user)
    {
        return $this->handleView($this->view($user));
    }

    public function deleteUserAction(User $user)
    {
    	$this->em->remove($user);
		$this->em->flush();
		return new Response();
    }
}
