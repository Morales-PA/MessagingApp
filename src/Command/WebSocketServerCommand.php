<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Workerman\Worker;
use App\Entity\Conversacion;
use App\Entity\Usuario;
use App\Entity\Mensaje;

#[AsCommand(name: 'app:start-websocket')]
class WebSocketServerCommand extends Command
{
    protected function configure(): void
    {
        $this->ignoreValidationErrors();
    }

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ws_worker = new Worker('websocket://0.0.0.0:2346');

        $ws_worker->onConnect = function ($connection) {
            echo "Someone connected!\n";
        };

        $ws_worker->onMessage = function ($connection, $data) use ($ws_worker) {

            if (false === $this->entityManager->getConnection()->isConnected()) {
                $this->entityManager->getConnection()->close();
            }

            echo "Received: $data\n";
            $payload = json_decode($data, true);

            if ($payload) {

                $conversacion = $this->entityManager->getRepository(Conversacion::class)->find($payload["conversationId"]);
                $usuario = $this->entityManager->getRepository(Usuario::class)->findOneBy(["nombre" => $payload["username"]]);

                if ($conversacion && $usuario) {

                    $mensaje = new Mensaje();
                    $mensaje->setConversacion($conversacion);
                    $mensaje->setUsuario($usuario);
                    $mensaje->setContenido($payload["content"]);
                    $mensaje->setFechaMensaje(new \DateTime());

                    $this->entityManager->persist($mensaje);
                    $this->entityManager->flush();

                    $payload["userId"] = $usuario->getIdUsuario();
                    $payload["time"] = date("H:i d/m/Y");

                    foreach ($ws_worker->connections as $clientConnection) {
                        $clientConnection->send(json_encode($payload));
                    }
                }
            }
        };

        $ws_worker->onClose = function ($connection) {
            echo "Someone disconnected\n";
        };

        Worker::runAll();

        return Command::SUCCESS;
    }
}