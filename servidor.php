<?php
// Conexão com o banco de dados MySQL
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'trabalho_pw1';

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
