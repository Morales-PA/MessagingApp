<?php

namespace App\Security;

use App\Entity\Usuario;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;

use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

use Symfony\Component\Security\Http\SecurityRequestAttributes;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private RouterInterface $router;
    private EntityManagerInterface $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username');

        // guardar el ultimo usuario escrito
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {

                $usuario = $this->entityManager
                    ->getRepository(Usuario::class)
                    ->findOneBy(['nombre' => $userIdentifier]);

                // Usuario no existe
                if (!$usuario) {
                    throw new CustomUserMessageAuthenticationException(
                        'Usuario no encontrado.'
                    );
                }

                // Cuenta no verificada
                if ($usuario->getToken() !== '') {
                    throw new CustomUserMessageAuthenticationException(
                        'Debes verificar tu correo antes de iniciar sesión.'
                    );
                }

                return $usuario;
            }),
            new PasswordCredentials($request->request->get('_password'))
        );
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?RedirectResponse
    {
        return new RedirectResponse($this->router->generate('app_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('login');
    }
}
