<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VinylController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response('Vinyl lol');
    }

    #[Route('/browse/{slug}')]
    public function browse(string $slug = null): Response
    {
        if (!$slug) {
            return new Response('Browse all the vinyl!');
        }
        $title = str_replace('-', ' ', $slug);
        $title = ucwords($title);

        return new Response('Genre: '.$title);
    }
}