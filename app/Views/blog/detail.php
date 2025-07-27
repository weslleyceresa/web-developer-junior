<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<style>
    .author-card:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }

    .post-content {
        /* Preserva espaços, tabs e quebras de linha do conteúdo */
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: inherit;
    }

    .sticky-back {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 1000;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <!-- Autor acima do card -->
            <div class="d-flex align-items-center author-card p-3 mb-4 rounded shadow-sm bg-white">
                <a href="/user/<?= esc($post['author_username']) ?>">
                    <img src="<?= base_url($post['avatar_path'] ?? 'uploads/avatars/default-avatar.png') ?>"
                        class="rounded-circle me-3 border shadow-sm"
                        width="56" height="56"
                        alt="Avatar de <?= esc($post['author_name']) ?>">
                </a>
                <div>
                    <a href="/user/<?= esc($post['author_username']) ?>" class="fw-bold text-dark text-decoration-none h5 mb-0 d-block">
                        <?= esc($post['author_name']) ?>
                    </a>
                    <small class="text-muted">Publicado em <?= date('d/m/Y \à\s H:i', strtotime($post['created_at'])) ?></small>
                </div>
            </div>

            <!-- Card do post -->
            <article class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="mb-4"><?= esc($post['title']) ?></h1>

                    <?php if (!empty($post['image_path'])): ?>
                        <img src="<?= base_url($post['image_path']) ?>" class="img-fluid mb-4 rounded" alt="Imagem de capa do post">
                    <?php endif; ?>

                    <!-- Conteúdo do post -->
                    <div class="post-content fs-5 lh-lg">
                        <?= $post['html_content'] ?>
                    </div>

                    <!-- Tags futuras ou outras infos -->
                    <div class="mt-5 d-flex gap-3 flex-wrap">
                        <span class="badge bg-light text-secondary"><i class="bi bi-clock"></i> <?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                        <span class="badge bg-light text-secondary"><i class="bi bi-person"></i> <?= esc($post['author_username']) ?></span>
                    </div>
                </div>
            </article>

            <!-- Botão flutuante para voltar -->
            <div class="sticky-back d-md-none">
                <a href="/blog" class="btn btn-outline-secondary rounded-circle shadow-sm" title="Voltar ao feed">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
            </div>

            <!-- Botão normal para desktop -->
            <div class="mt-4 d-none d-md-block">
                <a href="/blog" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar ao feed
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.card').hide().fadeIn(500);
    });
</script>

<?= $this->endSection() ?>