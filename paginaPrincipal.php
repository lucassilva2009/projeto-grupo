<?php
// Mantém compatibilidade com links antigos: o painel oficial é painel.php.
session_start();
if (isset($_SESSION['perfil']) && is_array($_SESSION['perfil'])) {
    header('Location: painel.php');
} else {
    header('Location: criacao%20de%20perfil.html');
}
exit;
