<?php
include 'servidor.php';

$mensagem = ''; // Variável para exibir mensagem de sucesso ou erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $descricao = $_POST['descricao'];
    $beneficios = $_POST['beneficios'];
    $cidade = $_POST['cidade'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    // Insere os dados no banco de dados
    try {
        $sql = "INSERT INTO empresa (cnpj, nome, email, endereco, descricao, beneficios, cidade, telefone, senha)
                VALUES ('$cnpj', '$nome', '$email', '$endereco', '$descricao', '$beneficios', '$cidade', '$telefone', '$senha')";
        $conn->query($sql);
        $mensagem = "Empresa cadastrada com sucesso!";
        header("Location: loginEmpresa.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $mensagem = "CNPJ já cadastrado. Tente outro CNPJ.";
        } else {
            $mensagem = "Erro ao cadastrar empresa: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Empresa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            <a class="nav-link text-white" aria-current="page" href="index.php">Feed</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Login
            </a>
            <ul class="dropdown-menu text-white">
              <li><a class="dropdown-item" href="loginEmpresa.php">Empresa</a></li>
              <li><a class="dropdown-item" href="loginCandidato.php">Candidato</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card p-4 shadow-sm">
          <h2 class="card-title text-center mb-4">Cadastro de Empresa</h2>
          
          <!-- Exibe mensagem de erro ou sucesso -->
          <?php if ($mensagem): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $mensagem; ?>
            </div>
          <?php endif; ?>

          <form class="row g-3" action="" method="post">
            <div class="col-md-6">
              <label for="nome" class="form-label">Nome da empresa</label>
              <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="col-md-6">
              <label for="cnpj" class="form-label">CNPJ</label>
              <input type="text" class="form-control" id="cnpj" name="cnpj" required>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-md-6">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
            <div class="col-12">
              <label for="endereco" class="form-label">Endereço</label>
              <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço completo" required>
            </div>
            <div class="col-md-6">
              <label for="descricao" class="form-label">Descrição da empresa</label>
              <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Breve resumo sobre o que faz">
            </div>
            <div class="col-md-6">
              <label for="beneficios" class="form-label">Benefícios oferecidos</label>
              <input type="text" class="form-control" id="beneficios" name="beneficios">
            </div>
            <div class="col-md-4">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="cidade" name="cidade" required>
          </div>
          <div class="col-md-4">
            <label for="telefone" class="form-label">Telefone para contato</label>
            <input type="text" class="form-control" id="telefone" name="telefone">
          </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                  Desejo receber atualizações sobre novas vagas
                </label>
              </div>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary w-50">Cadastrar Empresa</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>
