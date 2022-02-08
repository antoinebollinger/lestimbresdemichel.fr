<?php
session_start();
echo json_encode(array('nb_liste' => ((isset($_SESSION['list'])) ? count($_SESSION['list']['id']) : 0)));
?>