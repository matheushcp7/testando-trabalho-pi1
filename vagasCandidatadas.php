<?php
session_start();
include 'servidor.php'; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado e possui CPF na sessão
if (isset($_SESSION['cpf'])) {
    $cpf = $_SESSION['cpf'];

    // Verifica se há uma solicitação de exclusão
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id_post'], $_POST['delete_cnpj'])) {
        $id_post = $_POST['delete_id_post'];
        $cnpj = $_POST['delete_cnpj'];

        // Consulta para excluir a inscrição na vaga
        $sql_delete = "DELETE FROM vaga_candidato WHERE cpf = ? AND id_post = ? AND cnpj = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("sss", $cpf, $id_post, $cnpj);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Inscrição excluída com sucesso!'); window.location.href = 'vagasCandidatadas.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir inscrição. Tente novamente.');</script>";
        }

        $stmt_delete->close();
    }

    // Consulta para obter as vagas nas quais o candidato se inscreveu
    $sql = "
        SELECT vc.id_post, p.titulo, e.nome AS empresa_nome, p.localizacao, p.beneficios, vc.cnpj
        FROM vaga_candidato vc
        JOIN post p ON vc.id_post = p.id_post
        JOIN empresa e ON p.cnpj_empresa = e.cnpj
        WHERE vc.cpf = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf); // "s" indica que o parâmetro é uma string (cpf)
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    echo "<script>alert('Você precisa estar logado para acessar essa página.'); window.location.href = 'login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Inscrições</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navbar fixa no topo -->
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
                        <a class="nav-link text-white" aria-current="page" href="feedCandidato.php">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="vagasCandidatadas.php">Minhas Inscrições</a>
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

    <!-- Container da tabela de inscrições -->
    <div class="container mt-5">
        <h2 class="text-center">Minhas Inscrições</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID da Vaga</th>
                        <th>Título</th>
                        <th>Empresa</th>
                        <th>Localização</th>
                        <th>Benefícios</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_post']; ?></td>
                                <td><?php echo $row['titulo']; ?></td>
                                <td><?php echo $row['empresa_nome']; ?></td>
                                <td><?php echo $row['localizacao']; ?></td>
                                <td><?php echo $row['beneficios']; ?></td>
                                <td>
                                    <!-- Formulário para exclusão de inscrição -->
                                    <form method="POST" action="vagasCandidatadas.php" onsubmit="return confirm('Tem certeza que deseja excluir sua inscrição nesta vaga?');">
                                        <input type="hidden" name="delete_id_post" value="<?php echo $row['id_post']; ?>">
                                        <input type="hidden" name="delete_cnpj" value="<?php echo $row['cnpj']; ?>">
                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Você ainda não se inscreveu em nenhuma vaga.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
