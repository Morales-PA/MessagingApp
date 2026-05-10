-- Generado con ayuda de una LLM, solo para pruebas

USE MessageApp;

-- =====================
-- USUARIOS
-- =====================
INSERT INTO Usuarios (Correo, Nombre, Contrasena, Token) VALUES
('juan.perez@gmail.com', 'juan', '$2y$13$dcgbgGtqEwPC03Syimws1eSAZOZquJ2m1uQyhrjE.CVhNHRNmCHxy', ''),
('maria.lopez@gmail.com', 'maria', '$2y$13$dcgbgGtqEwPC03Syimws1eSAZOZquJ2m1uQyhrjE.CVhNHRNmCHxy', ''),
('carlos.garcia@gmail.com', 'carlos', '$2y$13$dcgbgGtqEwPC03Syimws1eSAZOZquJ2m1uQyhrjE.CVhNHRNmCHxy', 'token_carlos'),
('ana.martinez@gmail.com', 'ana', '$2y$13$dcgbgGtqEwPC03Syimws1eSAZOZquJ2m1uQyhrjE.CVhNHRNmCHxy', 'ºtoken_ana'),
('luis.hernandez@gmail.com', 'luis', '$2y$13$dcgbgGtqEwPC03Syimws1eSAZOZquJ2m1uQyhrjE.CVhNHRNmCHxy', 'token_luis');

-- =====================
-- CONTACTOS
-- =====================
INSERT INTO Contactos (IdUsuario, IdContacto, Estado) VALUES
(1, 2, 'aceptado'),
(2, 1, 'aceptado'),
(1, 3, 'aceptado'),
(3, 1, 'aceptado'),
(2, 4, 'pendiente'),
(4, 2, 'pendiente'),
(3, 5, 'aceptado'),
(5, 3, 'aceptado');

-- =====================
-- CONVERSACIONES
-- =====================
INSERT INTO Conversaciones (Nombre_Conversacion) VALUES
('Chat Juan y Maria'),
('Grupo Amigos'),
('Trabajo Proyecto');

-- =====================
-- MIEMBROS DE CONVERSACIONES
-- =====================
-- Conversación 1: Juan y Maria
INSERT INTO MiembrosConversaciones (IdConversacion, IdUsuario, Rol) VALUES
(1, 1, 'admin'),
(1, 2, 'miembro');

-- Conversación 2: Grupo Amigos
INSERT INTO MiembrosConversaciones (IdConversacion, IdUsuario, Rol) VALUES
(2, 1, 'admin'),
(2, 2, 'miembro'),
(2, 3, 'miembro'),
(2, 4, 'miembro');

-- Conversación 3: Trabajo Proyecto
INSERT INTO MiembrosConversaciones (IdConversacion, IdUsuario, Rol) VALUES
(3, 3, 'admin'),
(3, 4, 'miembro'),
(3, 5, 'miembro');

-- =====================
-- MENSAJES
-- =====================
INSERT INTO Mensajes (IdUsuario, IdConversacion, Contenido) VALUES
(1, 1, 'Hola Maria!'),
(2, 1, 'Hola Juan, que tal?'),
(1, 1, 'Todo bien 😊'),

(1, 2, 'Hola a todos'),
(2, 2, 'Hey!'),
(3, 2, 'Que pasa gente'),
(4, 2, 'Todo bien por aqui'),

(3, 3, 'Recordatorio del proyecto'),
(4, 3, 'Gracias!'),
(5, 3, 'Lo reviso luego');
