<?php

namespace App\Controller;

use App\Entity\Conversacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginOptionsController extends AbstractController
{
    #[Route('/crear-cuenta', name: 'create_account')]
    public function createAccount(): Response
    {
        return $this->render('createAccount.html.twig');
    }

    #[Route('/crear-cuenta/correo-enviado', name: 'create_account_email_sent')]
    public function createAccountEmailSent(): Response
    {
        //comprobar los parametros del formulario
        //si ya existe un usuario con ese nombre
        //si las contraseñas no coinciden
        //si algún campo está vacio
        //si el nombre de usuario tiene caracteres no validos

        return $this->render('createAccountEmailSent.html.twig');
    }

    #[Route('/recuperar-contraseña', name: 'reset_password')]
    public function resetPassword(): Response
    {
        return $this->render('resetPassword.html.twig');
    }

    #[Route('/recuperar-contraseña/correo-enviado', name: 'reset_password_email_sent')]
    public function resetPasswordEmailSent(): Response
    {
        //comprobar parametros enviados
        //si el usuario existe
        //si el campo está relleno

        return $this->render('resetPasswordEmailSent.html.twig');
    }


}
