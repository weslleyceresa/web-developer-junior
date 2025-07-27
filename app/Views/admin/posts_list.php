<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Gerenciar Posts</h2>
        <div>
            <a href="/admin/dashboard" class="btn btn-outline-secondary me-2">Voltar ao Dashboard</a>
            <a href="/admin/posts/new" class="btn btn-success">Novo Post</a>
        </div>
    </div>
    <form method="get" class="mb-3 d-flex" action="">
        <input type="text" name="search" value="<?= esc($search ?? '') ?>" class="form-control me-2" placeholder="Buscar por título ou autor...">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

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
                    <td>
                        <a href="/user/<?= esc($post['author_username']) ?>" class="d-flex align-items-center text-decoration-none">
                            <img src="/<?= esc($post['avatar_path'] ?? 'uploads/avatars/default-avatar.png') ?>"
                                alt="Avatar de <?= esc($post['author_name'] ?? 'Desconhecido') ?>"
                                style="width:32px; height:32px; border-radius:50%; object-fit:cover; margin-right:8px;">
                            <span><?= esc($post['author_name'] ?? 'Desconhecido') ?></span>
                        </a>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
                    <td>
                        <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="/blog/<?= esc($post['slug']) ?>" target="_blank" class="btn btn-sm btn-info">Ver</a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $post['id'] ?>)">Excluir</button>
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
    $(document).ready(function() {
        $('#postsTable').DataTable();
    });

    function deletePost(id) {
        if (confirm('Deseja realmente excluir este post?')) {
            window.location.href = '/admin/posts/delete/' + id;
        }

        function confirmDelete(postId) {
            if (confirm('Tem certeza que deseja excluir este post? Essa ação não poderá ser desfeita.')) {
                window.location.href = `/admin/posts/delete/${postId}`;
            }
        }
    }
</script>

<?= $this->endSection() ?>