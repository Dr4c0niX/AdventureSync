<?php

require("./layout/header.php");
// Vide la session pour déconnecter l'utilisateur
session_destroy();
echo "<script>window.location.href='index.php'</script>";