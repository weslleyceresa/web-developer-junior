<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Dashboard Administrativo</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total de Usuários</h5>
                    <p class="display-6"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Usuários Ativos</h5>
                    <p class="display-6"><?= $activeUsers ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Posts Publicados</h5>
                    <p class="display-6"><?= $totalPosts ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4>Últimos Usuários Cadastrados</h4>
    <ul class="list-group mb-4">
        <?php foreach ($recentUsers as $user): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?= esc($user['name']) ?> (<?= esc($user['username']) ?>)</span>
                <span class="text-muted small"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Últimos Posts Criados</h4>
    <ul class="list-group">
        <?php foreach ($recentPosts as $post): ?>
            <li class="list-group-item d-flex justify-content-between">
                <a href="/blog/<?= esc($post['slug']) ?>" target="_blank"><?= esc($post['title']) ?></a>
                <span class="text-muted small"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?= $this->endSection() ?>
