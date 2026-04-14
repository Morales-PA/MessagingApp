<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'Usuarios')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint', name: 'IdUsuario')]
    private ?int $idUsuario = null;

    #[ORM\Column(type: 'string', length: 50, name: 'Nombre', unique: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: 'string', length: 50, name: 'Apellidos')]
    private ?string $apellidos = null;

    #[ORM\Column(type: 'string', length: 500, name: 'Contrasena')]
    private ?string $contrasena = null;

    #[ORM\Column(type: 'string', length: 255, name: 'Token')]
    private ?string $token = null;

    #[ORM\Column(type: 'datetime', name: 'FechaAlta')]
    private ?\DateTimeInterface $fechaAlta = null;

    //Para el login
    public function getUserIdentifier(): string
    {
        return (string) $this->nombre;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return (string) $this->contrasena;
    }

    public function eraseCredentials(): void {}

    // GETTERS Y SETTERS

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    public function getContrasena(): ?string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getFechaAlta(): ?\DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;
        return $this;
    }
}
