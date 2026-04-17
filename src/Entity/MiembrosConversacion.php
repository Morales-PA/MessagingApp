<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'MiembrosConversaciones')]
class MiembrosConversacion
{
  #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Conversacion::class)]
    #[ORM\JoinColumn(name: 'IdConversacion', referencedColumnName: 'IdConversacion', onDelete: 'CASCADE')]
    private Conversacion $conversacion;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'IdUsuario', referencedColumnName: 'IdUsuario', onDelete: 'CASCADE')]
    private Usuario $usuario;

    #[ORM\Column(length: 10, name: 'Rol')]
    private string $rol = 'miembro';

    public function getConversacion(): Conversacion
    {
        return $this->conversacion;
    }

    public function setConversacion(Conversacion $conversacion): self
    {
        $this->conversacion = $conversacion;
        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;
        return $this;
    }
}
