<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChessController extends AbstractController
{
    // Ruta de prueba
    #[Route('/ajedrez/test-boton', name: 'app_chess_test_button')]
    public function testBoton(): Response
    {
        return new Response('
            <div style="text-align:center; margin-top:50px; font-family:Arial, sans-serif;">
                <h2>Simulador del Botón de Ajedrez con API Lichess</h2>
                <p>Al pulsar este botón, se simulará que estás en el Chat #999 y retas al usuario #888.</p>
                <p>Symfony llamará a Lichess en segundo plano y te redirigirá a la partida.</p>
                <br>
                <form action="/ajedrez/crear" method="POST" style="display: inline;" id="chessForm">
                    <input type="hidden" name="id_conversacion" value="999">
                    <input type="hidden" name="id_contrincante" value="888">
                    <button type="submit" style="
                        background: #22c55e;
                        color: white;
                        border: none;
                        padding: 15px 25px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 16px;
                        cursor: pointer;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    ">
                        🎮 Retar a Ajedrez (Probar API)
                    </button>
                </form>
            </div>
            <script>
                // Asegura que el formulario apunte correctamente a tu dominio local
                document.getElementById("chessForm").action = window.location.origin + "/ajedrez/crear";
            </script>
        ');
    }

    /**
     * Esta ruta se encarga de crear la partida en Lichess mediante su API y luego redirige al usuario a la mesa de juego oficial.
     */
    #[Route('/ajedrez/crear', name: 'app_chess_create', methods: ['POST'])]
    public function crearPartida(Request $request): Response
    {
        $idConversacion = $request->request->get('id_conversacion');
        $idContrincante = $request->request->get('id_contrincante');

        //  $this->getUser()->getId()
        $idYo = 1;

        $postData = [
            'variant' => 'standard',
            'timeMode' => 'unlimited', // Partida sin reloj, ideal para un chat de mensajería
            'name' => 'Partida MessageApp Chat #' . $idConversacion
        ];

        $ch = curl_init('https://lichess.org/api/challenge/open');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        // Cabeceras simulando navegador para evitar que Lichess bloquee la petición local
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 TFG-App');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        // Evita errores de certificados SSL en entornos de desarrollo local (localhost)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        // Validamos que Lichess nos devuelva las URLs oficiales para ambos bandos
        if (isset($data['urlWhite']) && isset($data['urlBlack'])) {
            $urlBlanco = $data['urlWhite'];
            $urlNegro = $data['urlBlack'];


            return $this->redirectToRoute('app_chess_game', [
                'urlBlanco' => $urlBlanco,
                'urlNegro' => $urlNegro,
                'idYo' => $idYo,
                'idContrincante' => $idContrincante
            ]);
        }

        return new Response('La API de Lichess cambió el formato o no es accesible. Respuesta: ' . htmlspecialchars($response), 500);
    }

    /**
     * En lugar de meter un iframe que los navegadores bloquean por seguridad (X-Frame-Options),
     * esta ruta redirige al usuario directamente a la mesa de juego oficial con su bando asignado.
     */
    #[Route('/ajedrez/juego', name: 'app_chess_game')]
    public function juego(Request $request): Response
    {
        $urlBlanco = $request->query->get('urlBlanco');
        $urlNegro = $request->query->get('urlNegro');
        $idYo = $request->query->get('idYo');

        // El creador del reto (ID 1 de simulación) va con blancas, el invitado con negras.
        // TODO: Tu compañero cambiará el '1' por la validación de la sesión real.
        $urlPartidaFinal = ($idYo == 1) ? $urlBlanco : $urlNegro;

        // Redirección directa del navegador: 100% real, interactivo y con sonidos nativos
        return $this->redirect($urlPartidaFinal);
    }
}
