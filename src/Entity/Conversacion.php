<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Conversaciones')]
class Conversacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'IdConversacion')]
    private ?int $idConversacion = null;

    #[ORM\Column(length: 50, name: 'Nombre_Conversacion')]
    private string $nombreConversacion;

    #[ORM\Column(type: 'datetime', name: 'FechaAlta', nullable: true)]
    private ?\DateTimeInterface $fechaAlta = null;

    public function getIdConversacion(): int
    {
        return $this->idConversacion;
    }

    public function getNombreConversacion(): string
    {
        return $this->nombreConversacion;
    }

    public function setNombreConversacion(string $nombre): self
    {
        $this->nombreConversacion = $nombre;
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
