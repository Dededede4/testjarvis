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

/**
 * @RouteResource("User", pluralize=false)
 */
class UserController extends FOSRestController
{

	public function postUserAction(Request $request)
	{
		$user = new User();
		$user->setCreationdate(new \DateTime());
		$user->setUpdatedate(new \DateTime());

		$form = $this->createForm(UserType::class, $user);
		$data = json_decode($request->getContent(), true);
		$form->submit($data);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			return new Response();
		}
		return new Response('Error', Response::HTTP_FORBIDDEN);
	}

	// L’API doit comprendre un listener utilisant Logger pour écrire dans les logs chaque modification d’un enregistrement.

	public function getUsersAction()
    {

    } // "get_users"            [GET] /users


    public function getUserAction(User $user)
    {
        return $this->handleView($this->view($user));
    }

    public function editUserAction($slug)
    {} // "edit_user"            [GET] /users/{slug}/edit

    public function deleteUserAction($slug)
    {} // "delete_user"          [DELETE] /users/{slug}
}
