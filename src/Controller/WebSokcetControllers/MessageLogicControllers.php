<?php

namespace App\Controller\WebSokcetControllers;

use App\Entity\Contacto;
use App\Entity\Conversacion;
use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessageLogicControllers extends AbstractController
{
    #[Route("/conversacion/{id}", name: "viewConversationContent")]
    public function controller1(Conversacion $conversacion, EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository("App\Entity\Mensaje")->findBy(
            ["conversacion" => $conversacion],
            ["fechaMensaje" => "ASC"]
        );

        return $this->render("webSockets/messages.html.twig", [
            "conversacion" => $conversacion,
            "mensajes" => $messages,
        ]);
    }

}