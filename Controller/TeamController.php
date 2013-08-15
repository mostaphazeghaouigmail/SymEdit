<?php

namespace Isometriks\Bundle\SymEditBundle\Controller;

use Isometriks\Bundle\SymEditBundle\Annotation\PageController as Bind;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Bind(name="symedit-team")
 */
class TeamController extends Controller
{
    /**
     * @Route("/", name="team")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userClass = $this->container->getParameter('fos_user.model.user.class');

        $query = $em->createQueryBuilder()
                    ->select('u')
                    ->from($userClass, 'u')
                    ->join('u.profile', 'p')
                    ->where('u.admin = true')
                    ->orderBy('p.lastName, p.firstName')
                    ->getQuery();

        $result = $query->getResult();

        $users = array_filter($result, function($user){
            return $user->getProfile()->getDisplay();
        });

        return $this->render('@SymEdit/Team/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/{slug}", name="team_view_slug")
     */
    public function viewAction($slug)
    {
        $profileClass = $this->container->getParameter('isometriks_symedit.entity.admin_profile_class');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($profileClass);

        $profile = $repo->findOneBySlug($slug);

        if(!$profile) {
            throw $this->createNotFoundException(sprintf('Could not find user with slug "%s".', $slug));
        }

        return $this->render('@SymEdit/Team/view.html.twig', array(
            'user' => $profile->getUser(),
        ));
    }
}