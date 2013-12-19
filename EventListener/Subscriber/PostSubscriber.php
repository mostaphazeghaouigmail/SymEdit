<?php

namespace Isometriks\Bundle\SymEditBundle\EventListener\Subscriber;

use Isometriks\Bundle\SeoBundle\Model\SeoInterface;
use Isometriks\Bundle\SymEditBundle\Event\Events;
use Sylius\Bundle\ResourceBundle\Event\ResourceEvent;
use Isometriks\Bundle\SymEditBundle\Event\PostEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class PostSubscriber implements EventSubscriberInterface
{
    protected $session;
    protected $seo;
    protected $router;

    public function __construct(Session $session, SeoInterface $seo, RouterInterface $router)
    {
        $this->session = $session;
        $this->seo = $seo;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::POST_CREATED => 'postCreated',
            Events::POST_UPDATED => 'postUpdated',
            Events::POST_VIEW => 'postView',
        );
    }

    public function postCreated(ResourceEvent $event)
    {
        /**
         * Add Created Notice
         */
        $this->session->getFlashBag()->add('notice', 'admin.post.flash.created');
        $this->addShare($event);
    }

    public function postUpdated(ResourceEvent $event)
    {
        $this->session->getFlashBag()->add('notice', 'admin.post.flash.updated');
        $this->addShare($event);
    }

    protected function addShare(ResourceEvent $event)
    {
        $post = $event->getSubject();

        if ($post->isPublished()) {
            try {
                $url = $this->router->generate('blog_slug_view', array('slug' => $post->getSlug()), true);
                $this->session->getFlashBag()->add('share', $url);
            } catch (RouteNotFoundException $e) {
                // The route to view posts doesn't exist, so we can't really share it.
            }
        }
    }

    public function postView(PostEvent $event)
    {
        $post = $event->getPost();
        $this->seo->setSubject($post);
    }
}