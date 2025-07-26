<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card p-4 mb-4">
        <div class="d-flex align-items-center">
            <img src="<?= base_url($user['avatar_path'] ?: 'uploads/avatars/default-avatar.png') ?>" class="me-3" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            <div>
                <h4><?= esc($user['name']) ?> <small class="text-muted">@<?= esc($user['username']) ?></small></h4>
                <p class="text-muted"><?= esc($user['bio']) ?></p>
            </div>
        </div>
    </div>

    <h5>Postagens de <?= esc($user['name']) ?></h5>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">Este usuário ainda não publicou nenhum post.</div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5><?= esc($post['title']) ?></h5>
                    <p class="text-muted"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
                    <a href="/blog/<?= esc($post['slug']) ?>" class="btn btn-sm btn-outline-primary">Ver post</a>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
