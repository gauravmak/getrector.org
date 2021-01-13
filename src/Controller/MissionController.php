<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MissionController extends AbstractController
{
    #[Route(path: 'mission', name: RouteName::MISSION)]
    public function __invoke(): Response
    {
        return $this->render('homepage/mission.twig');
    }
}
