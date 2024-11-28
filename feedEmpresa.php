<?php
session_start();
include 'servidor.php'; // Inclui a conexão com o banco de dados

$cnpj = $_SESSION['cnpj']; // Obtém o CNPJ da sessão
$mensagem = ''; // Variável para exibir mensagem de sucesso ou erro

// Consulta para obter as vagas postadas pela empresa, incluindo id_post
$sql = "SELECT p.id_post, p.titulo, e.nome, e.cidade 
        FROM post p 
        JOIN empresa e ON p.cnpj_empresa = e.cnpj 
        WHERE p.cnpj_empresa = '$cnpj'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOS EMPREGO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css">

  <style>
    /* Aumenta a distância entre o conteúdo e o footer */
    footer {
      margin-top: 50px; /* Ajuste a distância entre os posts e o footer */
      padding-top: 20px; /* Aumenta o espaçamento interno do footer */
      padding-bottom: 20px;
    }

    /* Aumenta o espaçamento entre os posts e o footer, especialmente para a última linha de posts */
    .container .row:last-child {
      margin-bottom: 50px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">SOS Emprego</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link text-white" aria-current="page" href="feedEmpresa.php">Feed</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" aria-current="page" href="postarVagaEmpresa.php">Postar vaga</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link text-white" href="Sair.php">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid p-0">
    <div class="card border-0">
      <img src="SOS_EMPRESO_Banner.jpg" class="w-100" alt="Imagem da logo" style="height: 350px; object-fit: cover; object-position: 30% 30%;">

    </div>
  </div>
  <div class="container mt-5">
    <h2 class="text-center mb-3 mt-0">Vagas Recentes:</h2>
    <div class="row mx-auto g-4">
      <?php
      if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
          echo '<div class="col-6 col-md-4 col-xxl-2">';
          echo '<div>';
          echo '<div class="card">';
          echo '<div class="card-body">';
          echo '<p class="text-center">' . $row["titulo"] . ' - ' . $row["nome"] . '</p>';
          echo '<p class="text-center">' . $row["cidade"] . '</p>';
          echo '</div>';
          echo '<div class="card-footer text-center">';
          echo '<a href="verVagaEmpresa.php?id_post=' . $row["id_post"] . '&cnpj=' . $cnpj . '" class="btn text-white" style="background-color: #002f6c; border-color: #002f6c;">Ver vaga</a>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo '<p class="text-center">Nenhuma vaga encontrada</p>';
      }
      ?>
    </div>

  </div>

  <footer class="text-center bg-dark text-white">
    <h5 class="card-title">Quem somos nós?</h5>
    <p class="card-text-end" style="font-size: 15px;">O SOS Emprego é uma plataforma dedicada a ajudar os moradores de Monte Carmelo em sua busca por oportunidades de trabalho nosso site conecta candidatos a empregadores locais, facilitando o processo de recrutamento e seleção.
      Seja você um jovem em busca do primeiro emprego ou um profissional experiente à procura de novos desafios, o SOS Emprego está aqui para apoiar sua jornada profissional.
    </p>
    <p class="card-text pt-3">2024 <i class="bi bi-c-circle"></i> Desenvolvido por Mariana e Matheus | Projeto fictício sem fins comerciais.</p>
  </footer>
</body>

</html>
