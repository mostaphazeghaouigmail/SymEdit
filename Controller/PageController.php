<?php

namespace Isometriks\Bundle\SymEditBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Isometriks\Bundle\SymEditBundle\Model\PageInterface;

class PageController extends Controller
{
    public function showAction(PageInterface $_page, Request $request)
    {
        $response = $this->createResponse($_page->getUpdatedAt());

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $this->render(sprintf('@SymEdit/Page/%s', $_page->getTemplate()), array(), $response);
    }
}
