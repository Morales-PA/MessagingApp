<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Contactos')]
class Contacto
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'IdUsuario', referencedColumnName: 'IdUsuario', onDelete: 'CASCADE')]
    private Usuario $usuario;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'IdContacto', referencedColumnName: 'IdUsuario', onDelete: 'CASCADE')]
    private Usuario $contacto;

    #[ORM\Column(type: 'string', length: 10, name: 'Estado')]
    private string $estado="pendiente";

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getContacto(): Usuario
    {
        return $this->contacto;
    }

    public function setContacto(Usuario $contacto): self
    {
        $this->contacto = $contacto;
        return $this;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }
}
