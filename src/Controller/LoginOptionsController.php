<?php

namespace App\Controller;

use App\Entity\Conversacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class LoginOptionsController extends AbstractController
{
    #[Route('/crear-cuenta', name: 'create_account')]
    public function createAccount(): Response
    {
        return $this->render('createAccount.html.twig');
    }

    #[Route('/crear-cuenta/correo-enviado', name: 'create_account_email_sent')]
    public function createAccountEmailSent(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer): Response
    {
        //comprobar los parametros del formulario
        //si ya existe un usuario con ese nombre
        //si las contraseñas no coinciden
        //si algún campo está vacio
        //si el nombre de usuario tiene caracteres no validos

        $newEmail = $request->request->get('create_email');
        $newUsername = $request->request->get('create_username');
        $newPassword = $request->request->get('create_password');
        $newRepeatPassword = $request->request->get('create_repeat_password');

        // comprobar campos vacíos
        if (
            empty($newEmail) ||
            empty($newUsername) ||
            empty($newPassword) ||
            empty($newRepeatPassword)
        ) {
            return $this->render('create_account.html.twig', [
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        // comprobar el formato del email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->render('create_account.html.twig', [
                'error' => 'El correo electrónico no es válido.'
            ]);
        }

        //  comprobar que las contraseñas coinciden
        if ($newPassword !== $newRepeatPassword) {
            return $this->render('create_account.html.twig', [
                'error' => 'Las contraseñas no coinciden.'
            ]);
        }

        // comprobar que el nombre de usuario solo tiene caracteres válidos
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $newUsername)) {
            return $this->render('create_account.html.twig', [
                'error' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.'
            ]);
        }

        // comprobar que el nombre de usuario no existe en la base de datos
        $existingUser = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['nombre' => $newUsername]);

        if ($existingUser) {
            return $this->render('create_account.html.twig', [
                'error' => 'Ese nombre de usuario ya existe.'
            ]);
        }

        // comprobar que el correo no existe en la base de datos
        $existingEmail = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['correo' => $newEmail]);

        if ($existingEmail) {
            return $this->render('create_account.html.twig', [
                'error' => 'Ese correo electrónico ya está registrado.'
            ]);
        }

        // crear el usuario en la base de datos después de todas las comprobaciones
        $user = new Usuario();
        $user->setCorreo($newEmail);
        $user->setNombre($newUsername);
        $user->setApellidos('');

        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setContrasena($hashedPassword);

        $token = bin2hex(random_bytes(32));

        $user->setToken($token);
        $user->setFechaAlta(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        // enviar correo de confirmación
        $email = (new Email())
        ->from('no-reply@messageapp.com')
        ->to($newEmail)
        ->subject('Confirma tu cuenta')
        ->html("
            <h1>Confirma tu cuenta</h1>
            <p>Haz click en el siguiente enlace:</p>
            <a href='http://localhost:8000/confirmarCuenta/$token'>
                Confirmar cuenta
            </a>
        ");

    $mailer->send($email);

    // =========================
    // TODO OK
    // =========================

    return $this->render('security/email_sent.html.twig', [
        'email' => $newEmail
    ]);
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
