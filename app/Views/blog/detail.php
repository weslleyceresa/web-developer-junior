<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2><?= esc($post['title']) ?></h2>
            <p class="text-muted mb-2">
                Publicado por <strong><?= esc($post['author_name']) ?></strong> em 
                <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
            </p>
            <hr>
            <div class="post-content">
                <?= $post['html_content'] ?>
            </div>
            <a href="/blog" class="btn btn-link mt-3">← Voltar ao feed</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
