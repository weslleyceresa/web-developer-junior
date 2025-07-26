<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Posts</h2>
        <a href="/admin/dashboard" class="btn btn-secondary">Voltar ao Dashboard</a>
    </div>

    <a href="/admin/posts/new" class="btn btn-success mb-3">Novo Post</a>

    <table class="table table-bordered table-hover" id="postsTable">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Slug</th>
                <th>Autor</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= esc($post['id']) ?></td>
                    <td><?= esc($post['title']) ?></td>
                    <td><?= esc($post['slug']) ?></td>
                    <td><?= esc($post['author'] ?? 'Desconhecido') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
                    <td>
                        <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="/blog/<?= esc($post['slug']) ?>" target="_blank" class="btn btn-sm btn-info">Ver</a>
                        <button class="btn btn-sm btn-danger" onclick="deletePost(<?= $post['id'] ?>)">Excluir</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Scripts DataTables e delete -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#postsTable').DataTable();
    });

    function deletePost(id) {
        if (confirm('Deseja realmente excluir este post?')) {
            window.location.href = '/admin/posts/delete/' + id;
        }
    }
</script>

<?= $this->endSection() ?>