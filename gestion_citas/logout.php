<?php
session_start();            // Inicia la sesión si aún no está iniciada
session_unset();            // Elimina todas las variables de sesión
session_destroy();          // Destruye la sesión actual

// Redirige al usuario a la página de inicio de sesión
header("Location: iniciar.html"); // Ajusta si el nombre de tu archivo es diferente
exit();
