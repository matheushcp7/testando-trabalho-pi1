<?php
session_start();
include 'servidor.php'; // Inclui a conexão com o banco de dados

// Obtendo os parâmetros 'id_post' e 'cnpj' da URL
$id_post = isset($_GET['id_post']) ? $_GET['id_post'] : null;
$cnpj = isset($_GET['cnpj']) ? $_GET['cnpj'] : null;

// Inicializa variáveis de erro e dados
$error = '';
$vaga = [];
$empresa = [];

if (isset($_SESSION['cpf'])) {
    $cpf = $_SESSION['cpf']; // Recupera o CPF da sessão
}



if ($id_post && $cnpj) {
    // Preparar e executar a consulta para obter informações da vaga e da empresa
    $sql = "
        SELECT e.*, p.*
        FROM empresa e
        JOIN post p ON e.cnpj = p.cnpj_empresa
        WHERE e.cnpj = ? AND p.id_post = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cnpj, $id_post);  // "ss" indica que ambos os parâmetros são strings
    $stmt->execute();
    $result = $stmt->get_result();
    // Verificar se há resultados
    if ($result->num_rows > 0) {
        // Preencher os dados da vaga e empresa
        $data = $result->fetch_assoc();
        $vaga = [
            'id_post' => $data['id_post'],
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
            'localizacao' => $data['localizacao'],
            'beneficios' => $data['beneficios'],
        ];
        $empresa = [
            'cnpj' => $data['cnpj'],
            'nome' => $data['nome'],
            'endereco' => $data['endereco'],
            'email' => $data['email'],
            'telefone' => $data['telefone'],
            'descricao' => $data['descricao'],
            'cidade' => $data['cidade'],
        ];
    } else {
        $error = "Vaga ou empresa não encontrada.";
    }

    // Fechar o statement
    $stmt->close();
} else {
    $error = "Parâmetros id_post ou cnpj ausentes.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('SOS_EMPRESO_Banner.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 70px;
            /* Espaço para a navbar fixa */
        }

        .container-info {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .vaga-container,
        .empresa-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .vaga-container h1,
        .empresa-container h1 {
            color: #002f6c;
        }
    </style>
    <title>Detalhes da Vaga</title>
</head>

<body class="bg-light">
    <!-- Navbar fixa no topo -->
    <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SOS Emprego</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="feedCandidato.php">Feed</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Containers para informações da vaga e da empresa -->
    <div class="container container-info">
        <div class="row">
            <!-- Container de informações sobre a vaga -->
            <div class="col-md-6 mb-4">
                <div class="vaga-container">
                    <h1>Detalhes da Vaga</h1>
                    <?php if ($error): ?>
                        <p class="text-danger"><?php echo $error; ?></p>
                    <?php else: ?>
                        <p><strong>ID da Vaga:</strong> <?php echo $vaga['id_post']; ?></p>
                        <p><strong>Título:</strong> <?php echo $vaga['titulo']; ?></p>
                        <p><strong>Descrição:</strong> <?php echo $vaga['descricao']; ?></p>
                        <p><strong>Localização:</strong> <?php echo $vaga['localizacao']; ?></p>
                        <p><strong>Benefícios:</strong> <?php echo $vaga['beneficios']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Container de informações sobre a empresa -->
            <div class="col-md-6 mb-4">
                <div class="empresa-container">
                    <h1>Sobre a Empresa</h1>
                    <?php if ($error): ?>
                        <p class="text-danger"><?php echo $error; ?></p>
                    <?php else: ?>
                        <p><strong>CNPJ:</strong> <?php echo $empresa['cnpj']; ?></p>
                        <p><strong>Nome:</strong> <?php echo $empresa['nome']; ?></p>
                        <p><strong>Endereço:</strong> <?php echo $empresa['endereco']; ?></p>
                        <p><strong>Email:</strong> <?php echo $empresa['email']; ?></p>
                        <p><strong>Telefone:</strong> <?php echo $empresa['telefone']; ?></p>
                        <p><strong>Descrição:</strong> <?php echo $empresa['descricao']; ?></p>
                        <p><strong>Cidade:</strong> <?php echo $empresa['cidade']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="container container-info">
                <div class="row">
                    <!-- Container para o botão de inscrição -->
                    <div class="col-12 mb-4">
                        <?php if ($error): ?>
                            <p class="text-danger"><?php echo $error; ?></p>
                        <?php else: ?>
                            <!-- Formulário de inscrição -->
                            <form method="POST" action="">
                                <input type="hidden" name="cpf" value="<?php echo $_SESSION['cpf']; ?>">
                                <input type="hidden" name="id_post" value="<?php echo $vaga['id_post']; ?>">
                                <input type="hidden" name="cnpj" value="<?php echo $empresa['cnpj']; ?>">
                                <button type="submit" class="btn btn-primary">Inscrever-se na Vaga</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>



<?php
// Verificar se o formulário foi enviado e processar a inscrição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf']; // CPF do candidato
    $id_post = $_POST['id_post']; // ID da vaga
    $cnpj = $_POST['cnpj']; // CNPJ da empresa

    // Verificar se os dados necessários estão presentes
    if ($cpf && $id_post && $cnpj) {
        // Preparar a consulta para inserir os dados na tabela vaga_candidato
        $sql = "INSERT INTO vaga_candidato (cpf, id_post, cnpj) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $cpf, $id_post, $cnpj); // Usa prepared statements para evitar SQL injection

        if ($stmt->execute()) {
            echo "<script>alert('Inscrição realizada com sucesso!'); window.location.href = 'feedCandidato.php';</script>";
        } else {
            echo "<script>alert('Erro ao se inscrever. Tente novamente.');</script>";
        }

        // Fechar a consulta
        $stmt->close();
    } else {
        echo "<script>alert('Dados incompletos. Tente novamente.');</script>";
    }
}
?>