<?php

namespace App\Controller;

use App\Entity\Conversacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainControllers extends AbstractController
{
    #[Route('/contactos', name: 'contactsPetitions')]
    public function contactos(): Response
    {
        return $this->render('contactos/index.html.twig');
    }

    #[Route('/conversaciones', name: 'conversations')]
    public function conversaciones(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $conversaciones = $em->createQuery(
            "SELECT c
             FROM App\Entity\Conversacion c
             JOIN App\Entity\MiembrosConversacion m
             WITH m.conversacion = c
             WHERE m.usuario = :user"
        )
        ->setParameter('user', $user)
        ->getResult();

        return $this->render('conversations.html.twig', [
            'conversaciones' => $conversaciones
        ]);
    }
}