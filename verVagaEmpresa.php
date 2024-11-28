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

// Verifica se o CNPJ da empresa está presente
if ($id_post && $cnpj) {
    // Consulta para obter informações da vaga e da empresa
    $sql = "
        SELECT e.*, p.*
        FROM empresa e
        JOIN post p ON e.cnpj = p.cnpj_empresa
        WHERE e.cnpj = ? AND p.id_post = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cnpj, $id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar se há resultados
    if ($result->num_rows > 0) {
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
    $stmt->close();
} else {
    $error = "Parâmetros id_post ou cnpj ausentes.";
}

// Processa a exclusão da vaga se o botão de exclusão for clicado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir_vaga'])) {
    $id_post = $_POST['id_post'];
    $sql_delete = "DELETE FROM post WHERE id_post = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("s", $id_post);

    if ($stmt_delete->execute()) {
        $stmt_delete->close();
        header("Location: feedEmpresa.php");
        exit();
    } else {
        echo "<script>alert('Erro ao excluir a vaga.');</script>";
    }
    $stmt_delete->close();
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
        }
        .container-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
            width: 100%;
            max-width: 600px; /* Aumente o tamanho do container */
        }
        .vaga-container {
            background-color: #fff;
            padding: 30px; /* Aumente o padding para mais espaço interno */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
        }
        .vaga-container h1 {
            color: #002f6c;
        }
        .btn-danger {
            margin-top: 20px;
        }
    </style>
    <title>Detalhes da Vaga</title>
</head>
<body class="bg-light">
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
                        <a class="nav-link text-white" href="feedEmpresa.php">Feed</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-info">
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
            <?php if (!$error): ?>
                <form method="POST" action="">
                    <input type="hidden" name="id_post" value="<?php echo $vaga['id_post']; ?>">
                    <button type="submit" name="excluir_vaga" class="btn btn-danger">Excluir Vaga</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
