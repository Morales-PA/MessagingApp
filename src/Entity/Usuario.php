<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'Usuarios')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdUsuario')]
    private ?int $idUsuario = null;

    #[ORM\Column(length: 50, unique: true, name: 'Correo')]
    private string $correo;

    #[ORM\Column(length: 50, unique: true, name: 'Nombre')]
    private string $nombre;

    #[ORM\Column(length: 50, name: 'Apellidos')]
    private string $apellidos;

    #[ORM\Column(length: 500, name: 'Contrasena')]
    private string $contrasena;

    #[ORM\Column(length: 255, name: 'Token')]
    private string $token;

    #[ORM\Column(type: 'datetime', name: 'FechaAlta', nullable: true)]
    private ?\DateTimeInterface $fechaAlta = null;

    // Para el login
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

    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    public function getCorreo(): string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;
        return $this;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): self
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getFechaAlta(): \DateTimeInterface
    {
        return $this->fechaAlta;
    }

    public function setFechaAlta(\DateTimeInterface $fechaAlta): self
    {
        $this->fechaAlta = $fechaAlta;
        return $this;
    }
}
