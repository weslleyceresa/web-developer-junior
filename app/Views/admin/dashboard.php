<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<div class="container py-4">
    <h2 class="mb-4">Painel Administrativo</h2>

    <!-- KPIs -->
    <div class="row mb-4 text-white">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary shadow text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-2"></i>
                    <h5>Total de Usuários</h5>
                    <p class="display-6"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success shadow text-center">
                <div class="card-body">
                    <i class="bi bi-person-check-fill fs-2"></i>
                    <h5>Usuários Ativos</h5>
                    <p class="display-6"><?= $activeUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning shadow text-center">
                <div class="card-body">
                    <i class="bi bi-journal-text fs-2"></i>
                    <h5>Posts Publicados</h5>
                    <p class="display-6"><?= $totalPosts ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card mb-5">
        <div class="card-header">Distribuição de Usuários</div>
        <div class="card-body d-flex justify-content-center">
            <div class="chart-container mx-auto" style="max-width: 400px;">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabela de Posts -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Últimos Posts Criados</span>
            <a href="/admin/posts" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-gear"></i> Gerenciar Posts
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="postsTable">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Slug</th>
                        <th>Data</th>
                        <th>Autor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                        <tr>
                            <td><?= esc($post['title']) ?></td>
                            <td><?= esc($post['slug']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="/user/<?= esc($post['author_username']) ?>" class="d-flex align-items-center text-decoration-none">
                                    <img src="/<?= esc($post['avatar_path'] ?? 'uploads/avatars/default-avatar.png') ?>"
                                        alt="Avatar de <?= esc($post['author_name']) ?>"
                                        style="width:32px; height:32px; border-radius:50%; object-fit:cover; margin-right:8px;">
                                    <span><?= esc($post['author_name']) ?></span>
                                </a>
                            </td>
                            <td>
                                <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <button class="btn btn-sm btn-danger" onclick="deletePost(<?= $post['id'] ?>)">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    // Chart.js gráfico de pizza
    const ctx = document.getElementById('userChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Ativos', 'Inativos'],
            datasets: [{
                label: 'Usuários',
                data: [<?= $activeUsers ?>, <?= $totalUsers - $activeUsers ?>],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        }
    });

    // DataTable
    $(document).ready(function() {
        $('#postsTable').DataTable();
    });

    // Excluir post (exemplo simples)
    function deletePost(postId) {
        if (confirm('Tem certeza que deseja excluir este post?')) {
            window.location.href = '/admin/posts/delete/' + postId;
        }
    }
</script>

<?= $this->endSection() ?>