<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Mensajes')]
class Mensaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdMensaje')]
    private ?int $idMensaje = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'IdUsuario', referencedColumnName: 'IdUsuario', onDelete: 'CASCADE')]
    private Usuario $usuario;

    #[ORM\ManyToOne(targetEntity: Conversacion::class)]
    #[ORM\JoinColumn(name: 'IdConversacion', referencedColumnName: 'IdConversacion', onDelete: 'CASCADE')]
    private Conversacion $conversacion;

    #[ORM\Column(length: 500, name: 'Contenido')]
    private string $contenido;

    #[ORM\Column(type: 'datetime', name: 'FechaMensaje', nullable: true)]
    private ?\DateTimeInterface $fechaMensaje = null;

    public function getIdMensaje(): int
    {
        return $this->idMensaje;
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

    public function getConversacion(): Conversacion
    {
        return $this->conversacion;
    }

    public function setConversacion(Conversacion $conversacion): self
    {
        $this->conversacion = $conversacion;
        return $this;
    }

    public function getContenido(): string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;
        return $this;
    }

    public function getFechaMensaje(): \DateTimeInterface
    {
        return $this->fechaMensaje;
    }

    public function setFechaMensaje(\DateTimeInterface $fecha): self
    {
        $this->fechaMensaje = $fecha;
        return $this;
    }
}
