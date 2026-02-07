<?php
/**
 * Verificação de caminhos e inclusão de dependências
 * * Usamos realpath() para garantir que o PHP encontre o caminho físico correto no Windows/Linux
 */
$configPath = realpath(__DIR__ . '/../../src/config.php');
$dataSourcePath = realpath(__DIR__ . '/../../src/class/dataSource.php');

if ($configPath) require_once $configPath;

if ($dataSourcePath) {
    require_once $dataSourcePath;
} else {
    die("Erro Crítico: O arquivo 'src/class/dataSource.php' não foi encontrado. Verifique a estrutura de pastas.");
}

use Dsource\DataSource;


// Verifica se a classe existe no arquivo carregado (tratando possíveis Namespaces)
if (!class_exists('dataSource')) {
    // Tenta com a primeira letra maiúscula caso seu arquivo siga esse padrão
    if (class_exists('DataSource')) {
        $db = new DataSource();
    } else {
        die("Erro: A classe 'dataSource' não está definida dentro do arquivo incluído.");
    }
} else {
    $db = new dataSource();
}

$conn = $db->getConnection();

// Verificação de segurança para a variável de conexão
if (!$conn) {
    die("Erro: Não foi possível estabelecer conexão com o banco de dados.");
}

// Filtro por status via GET
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'geral';

$sql = "SELECT id_associados, nome, email, celular, foto, id_status FROM associados_25";
if ($status_filter === 'ativos') {
    $sql .= " WHERE id_status = 1";
} elseif ($status_filter === 'inativos') {
    $sql .= " WHERE id_status = 0";
}
$sql .= " ORDER BY nome ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$associados = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Associados - ATCPE</title>
    <link rel="stylesheet" href="../../src/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="admin-container">
    <aside class="sidebar">
        <h2>Painel ATCPE</h2>
        <nav>
            <a href="dashboard.php?status=geral" class="<?= $status_filter == 'geral' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Geral
            </a>
            <a href="dashboard.php?status=ativos" class="<?= $status_filter == 'ativos' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i> Ativos
            </a>
            <a href="dashboard.php?status=inativos" class="<?= $status_filter == 'inativos' ? 'active' : '' ?>">
                <i class="fas fa-user-slash"></i> Inativos
            </a>
            <a href="../../index.php">
                <i class="fas fa-home"></i> Voltar ao Site
            </a>
        </nav>
    </aside>

    <main class="content">
        <header>
            <h1>Gestão de Associados (<?= ucfirst($status_filter) ?>)</h1>
        </header>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($associados)): ?>
                        <tr><td colspan="6" style="text-align:center;">Nenhum associado encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($associados as $assoc): ?>
                        <tr id="row-<?= $assoc['id_associados'] ?>">
                            <td>
                                <img src="../../assets/foto/<?= !empty($assoc['foto']) ? $assoc['foto'] : 'default.png' ?>" 
                                     alt="Foto" class="thumb-user" onerror="this.src='../../assets/foto/default.png'">
                            </td>
                            <td><?= htmlspecialchars($assoc['nome']) ?></td>
                            <td><?= htmlspecialchars($assoc['email']) ?></td>
                            <td><?= htmlspecialchars($assoc['celular']) ?></td>
                            <td>
                                <span class="badge <?= $assoc['id_status'] == 1 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $assoc['id_status'] == 1 ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="toggleStatus(<?= $assoc['id_associados'] ?>, <?= $assoc['id_status'] ?>)" 
                                        class="btn-status" title="Ativar/Desativar">
                                    <i class="fas <?= $assoc['id_status'] == 1 ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                </button>
                                
                                <a href="admin_edit_profile.php?id=<?= $assoc['id_associados'] ?>" class="btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button onclick="deleteAssociate(<?= $assoc['id_associados'] ?>)" class="btn-delete" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
async function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    const response = await fetch('../api/admin_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=toggle_status&id=${id}&status=${newStatus}`
    });
    const res = await response.json();
    if(res.success) {
        location.reload();
    } else {
        alert('Erro: ' + res.message);
    }
}

async function deleteAssociate(id) {
    if(!confirm('Tem certeza que deseja excluir este associado permanentemente?')) return;
    
    const response = await fetch('../api/admin_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=delete&id=${id}`
    });
    const res = await response.json();
    if(res.success) {
        document.getElementById(`row-${id}`).remove();
    } else {
        alert('Erro ao excluir: ' + res.message);
    }
}
</script>

</body>
</html>