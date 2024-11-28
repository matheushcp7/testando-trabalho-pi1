<?php
session_start();
include 'servidor.php'; // Inclui a conexão com o banco de dados

$mensagem = ''; // Variável para exibir mensagem de sucesso ou erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Captura os dados do formulário
  $titulo = $_POST['nomeVaga'];
  $descricao = $_POST['descricao'];
  $requisitos = $_POST['requisitos'];
  $beneficios = $_POST['beneficios'];
  $localizacao = $_POST['localizacao'];
  $cnpj = $_SESSION['cnpj']; // Obtém o CNPJ da sessão, definido no login

  // Insere os dados no banco de dados
  try {
    $sql = "INSERT INTO post (titulo, descricao, requisitos, beneficios, localizacao, cnpj_empresa)
                VALUES ('$titulo', '$descricao', '$requisitos', '$beneficios', '$localizacao', '$cnpj')";
    $conn->query($sql);
    $mensagem = "Vaga publicada com sucesso!";
  } catch (mysqli_sql_exception $e) {
    $mensagem = "Erro ao publicar vaga: " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Postagem empresa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <style>
  .btn-publicar {
    background-color: #002f6c;
    color: white;
  }
</style>
</head>



<body class="bg-light">
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
            <a class="nav-link text-white" href="feedEmpresa.php">Feed</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="postarVagaEmpresa.php">Postar vaga</a>
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
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card p-4 shadow-sm">
        <h2 class="card-title text-center mb-4" style="color: #002f6c;">Postar Vaga</h2>

          <!-- Exibe a mensagem de sucesso ou erro -->
          <?php if ($mensagem): ?>
            <div class="alert alert-info" role="alert">
              <?php echo $mensagem; ?>
            </div>
          <?php endif; ?>

          <form class="row g-3" method="POST">
            <div class="col-md-12">
              <label for="nomeVaga" class="form-label">Título da Vaga</label>
              <input type="text" class="form-control" id="nomeVaga" name="nomeVaga" required>
            </div>
            <div class="col-md-12">
              <label for="descricao" class="form-label">Descrição da Vaga</label>
              <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
            </div>
            <div class="col-md-12">
              <label for="requisitos" class="form-label">Requisitos</label>
              <textarea class="form-control" id="requisitos" name="requisitos" rows="4" required></textarea>
            </div>
            <div class="col-md-12">
              <label for="beneficios" class="form-label">Benefícios</label>
              <textarea class="form-control" id="beneficios" name="beneficios" rows="4" required></textarea>
            </div>
            <div class="col-md-6">
              <label for="localizacao" class="form-label">Localização</label>
              <input type="text" class="form-control" id="localizacao" name="localizacao" required>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn w-50 btn-publicar">Publicar Vaga</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
