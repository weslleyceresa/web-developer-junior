<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<style>
    .profile-header {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
    }
    .profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #0d6efd;
        box-shadow: 0 0 8px rgba(13,110,253,.25);
        transition: transform 0.3s ease;
    }
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    .post-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.15);
        transition: box-shadow 0.3s ease;
    }
</style>

<div class="container mt-5">
    <div class="profile-header d-flex align-items-center gap-4">
        <img src="<?= base_url($user['avatar_path'] ?: 'uploads/avatars/default-avatar.png') ?>" alt="Avatar de <?= esc($user['name']) ?>" class="profile-avatar">
        <div>
            <h2 class="mb-1"><?= esc($user['name']) ?> <small class="text-muted fs-5">@<?= esc($user['username']) ?></small></h2>
            <?php if (!empty($user['bio'])): ?>
                <p class="text-secondary fs-6 mb-0"><?= esc($user['bio']) ?></p>
            <?php else: ?>
                <p class="text-muted fst-italic">Este usuário ainda não adicionou uma biografia.</p>
            <?php endif ?>
        </div>
    </div>

    <h4 class="mb-3">Postagens de <?= esc($user['name']) ?></h4>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i> Este usuário ainda não publicou nenhum post.
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($posts as $post): ?>
                <div class="col-12 col-md-6">
                    <div class="card post-card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= esc($post['title']) ?></h5>
                            <p class="text-muted mb-3"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
                            <a href="/blog/<?= esc($post['slug']) ?>" class="btn btn-primary mt-auto align-self-start">
                                Ver post <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
