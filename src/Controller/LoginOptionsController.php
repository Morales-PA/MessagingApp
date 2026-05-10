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

    #[Route('/crear-cuenta/correo-enviado', name: 'create_account_email_sent', methods: ['POST'])]
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
            return $this->render('createAccount.html.twig', [
                'error' => 'Todos los campos son obligatorios.'
            ]);
        }

        // comprobar el formato del email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->render('createAccount.html.twig', [
                'error' => 'El correo electrónico no es válido.'
            ]);
        }

        //  comprobar que las contraseñas coinciden
        if ($newPassword !== $newRepeatPassword) {
            return $this->render('createAccount.html.twig', [
                'error' => 'Las contraseñas no coinciden.'
            ]);
        }

        // comprobar que el nombre de usuario solo tiene caracteres válidos
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $newUsername)) {
            return $this->render('createAccount.html.twig', [
                'error' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.'
            ]);
        }

        // comprobar que el nombre de usuario no existe en la base de datos
        $existingUser = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['nombre' => $newUsername]);

        if ($existingUser) {
            return $this->render('createAccount.html.twig', [
                'error' => 'Ese nombre de usuario ya existe.'
            ]);
        }

        // comprobar que el correo no existe en la base de datos
        $existingEmail = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['correo' => $newEmail]);

        if ($existingEmail) {
            return $this->render('createAccount.html.twig', [
                'error' => 'Ese correo electrónico ya está registrado.'
            ]);
        }

        // crear el usuario en la base de datos después de todas las comprobaciones
        $user = new Usuario();
        $user->setCorreo($newEmail);
        $user->setNombre($newUsername);

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
            ->subject("🚀 Confirma tu cuenta en MessageApp")
            ->html("
    <div style='
        background-color:#0f172a;
        padding:40px;
        font-family:Arial, sans-serif;
        color:white;
        text-align:center;
    '>

        <div style='
            max-width:600px;
            margin:auto;
            background:#1e293b;
            border-radius:20px;
            padding:40px;
            box-shadow:0 0 25px rgba(0,0,0,0.4);
        '>

            <h1 style='
                font-size:42px;
                margin-bottom:10px;
                color:#38bdf8;
            '>
                MessageApp
            </h1>

            <p style='
                color:#94a3b8;
                font-size:18px;
                margin-bottom:35px;
            '>
                Tu nueva plataforma de mensajería
            </p>

            <h2 style='font-size:28px; margin-bottom:20px;'>
                👋 Hola, $newUsername
            </h2>

            <p style='
                font-size:17px;
                line-height:1.7;
                color:#e2e8f0;
                margin-bottom:30px;
            '>
                Gracias por crear una cuenta en <b>MessageApp</b>.
                Solo queda un último paso para activar tu cuenta.
            </p>

            <a href='http://localhost:8000/confirmarCuenta/$token'
               style='
                    display:inline-block;
                    background:#38bdf8;
                    color:#0f172a;
                    text-decoration:none;
                    padding:18px 35px;
                    border-radius:12px;
                    font-size:18px;
                    font-weight:bold;
                    margin-bottom:35px;
               '>
               ✅ Confirmar cuenta
            </a>

            <p style='
                color:#94a3b8;
                font-size:14px;
                margin-top:20px;
                line-height:1.6;
            '>
                Si no has solicitado esta cuenta, puedes ignorar este correo.
            </p>

            <hr style='
                border:none;
                border-top:1px solid #334155;
                margin:35px 0;
            '>

            <p style='
                color:#64748b;
                font-size:13px;
            '>
                © 2026 MessageApp · Todos los derechos reservados
            </p>

        </div>

    </div>
    ");

        $mailer->send($email);

        return $this->render('createAccountEmailSent.html.twig', [
            'email' => $newEmail
        ]);
    }

    #[Route('/confirmarCuenta/{token}', name: 'verify_account')]
    public function verifyAccount(
        string $token,
        EntityManagerInterface $entityManager
    ): Response {
        // Buscar usuario por token
        $usuario = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['token' => $token]);

        // Si no existe
        if (!$usuario) {
            return $this->render('/ConfirmNewAccount.html.twig', [
                'success' => false,
                'message' => 'El enlace de verificación no es válido o ha expirado.'
            ]);
        }

        $usuario->setToken('');

        $entityManager->flush();

        return $this->render('/ConfirmNewAccount.html.twig', [
            'success' => true,
            'message' => 'Tu cuenta ha sido verificada correctamente.'
        ]);
    }

    #[Route('/recuperar-contraseña', name: 'reset_password')]
    public function resetPassword(): Response
    {
        return $this->render('resetPassword.html.twig');
    }

    #[Route('/recuperar-contraseña/correo-enviado', name: 'reset_password_email_sent', methods: ['POST'])]
    public function resetPasswordEmailSent(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $resetUsername = trim(
            $request->request->get('reset_username', '')
        );

        // si el campo está vacío
        if (empty($resetUsername)) {

            return $this->render('/resetPassword.html.twig', [
                'error' => 'Debes introducir un nombre de usuario.'
            ]);
        }

        // Buscar usuario por nombre
        $usuario = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['nombre' => $resetUsername]);

        //si no existe el usuario
        if (!$usuario) {

            return $this->render('/resetPassword.html.twig', [
                'error' => 'No existe ningún usuario con ese nombre.'
            ]);
        }

        //si exite el usuario
        // Generar nuevo token temporal
        $token = md5($usuario->getCorreo());

        $usuario->setToken($token);

        $entityManager->flush();

        // Email usuario
        $correo = $usuario->getCorreo();

        // Enviar correo de restablecimiento
        $email = (new Email())
            ->from('no-reply@messageapp.com')
            ->to($correo)
            ->subject('🔒 Restablecer contraseña')
            ->html("
                <div style='
                    font-family: Arial, sans-serif;
                    padding: 30px;
                    background: #f8fafc;
                    color: #0f172a;
                '>

                    <h1 style='color:#2563eb;'>
                        Restablecer contraseña
                    </h1>

                    <p>
                        Hola <b>{$usuario->getNombre()}</b>,
                    </p>

                    <p>
                        Hemos recibido una solicitud para
                        restablecer tu contraseña.
                    </p>

                    <p>
                        Haz click en el siguiente enlace:
                    </p>

                    <a href='http://localhost:8000/restablecer-contraseña/$token'
                       style='
                            display:inline-block;
                            padding:14px 24px;
                            background:#2563eb;
                            color:white;
                            text-decoration:none;
                            border-radius:10px;
                            font-weight:bold;
                       '>
                        Restablecer contraseña
                    </a>

                    <p style='margin-top:30px; color:#64748b;'>
                        Si no has solicitado este cambio,
                        puedes ignorar este correo.
                    </p>

                </div>
            ");

        $mailer->send($email);

        return $this->render('/ResetPasswordEmailSent.html.twig', [
            'email' => $correo
        ]);
    }

    #[Route('/restablecer-contraseña/{token}', name: 'reset_password_form')]
    public function restablecerContraseña(
        string $token,
        EntityManagerInterface $entityManager
    ): Response {
        // Buscar usuario por token
        $usuario = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['token' => $token]);

        // Token inválido
        if (!$usuario) {

            return $this->render('WriteNewPassword.html.twig', [
                'error' => 'El enlace de recuperación no es válido o ha expirado.'
            ]);
        }

        // Mostrar formulario
        return $this->render('WriteNewPassword.html.twig', [
            'token' => $token
        ]);
    }

    #[Route('/guardar-nueva-password/{token}', name: 'save_new_password', methods: ['POST'])]
    public function saveNewPassword(
        string $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Buscar usuario por token
        $usuario = $entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['token' => $token]);

        // Token inválido
        if (!$usuario) {

            return $this->render('WriteNewPassword.html.twig', [
                'error' => 'El enlace de recuperación no es válido o ha expirado.'
            ]);
        }

        // Obtener passwords
        $newPassword = $request->request->get('new_password', '');
        $repeatNewPassword = $request->request->get('repeat_new_password', '');

        // Campos vacíos
        if (empty($newPassword) || empty($repeatNewPassword)) {

            return $this->render('WriteNewPassword.html.twig', [
                'error' => 'Debes rellenar todos los campos.',
                'token' => $token
            ]);
        }

        // Passwords distintas
        if ($newPassword !== $repeatNewPassword) {

            return $this->render('WriteNewPassword.html.twig', [
                'error' => 'Las contraseñas no coinciden.',
                'token' => $token
            ]);
        }

        // Contraseña corta
        if (strlen($newPassword) < 6) {

            return $this->render('WriteNewPassword.html.twig', [
                'error' => 'La contraseña debe tener al menos 6 caracteres.',
                'token' => $token
            ]);
        }

        // Hashear nueva contraseña
        $hashedPassword = $passwordHasher->hashPassword(
            $usuario,
            $newPassword
        );

        $usuario->setContrasena($hashedPassword);

        // Invalidar token
        $usuario->setToken('');

        $entityManager->flush();

        // Vista éxito
        return $this->render('resetPasswordSuccess.html.twig');
    }
}
