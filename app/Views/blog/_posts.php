<?php if (empty($posts)) : ?>
    <div class="alert alert-info text-center py-4">
        <i class="bi bi-info-circle me-2"></i> Nenhum post encontrado. Seja o primeiro a publicar!
    </div>
<?php else : ?>
    <?php foreach ($posts as $post) : ?>
        <div class="card mb-4 shadow-sm post-card" tabindex="0" role="article" aria-labelledby="post-title-<?= esc($post['slug']) ?>">
            <div class="card-body">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?= base_url($post['image_path']) ?>"
                        class="img-fluid mb-3 rounded"
                        alt="Imagem de capa de <?= esc($post['title']) ?>">
                <?php endif; ?>
                <div class="d-flex align-items-center mb-3">
                    <a href="/user/<?= esc($post['author_username']) ?>" class="d-flex align-items-center text-decoration-none" aria-label="Ver perfil de <?= esc($post['author_name']) ?>">
                        <img src="<?= base_url($post['avatar_path'] ?? 'uploads/avatars/default-avatar.png') ?>"
                            class="rounded-circle me-3"
                            width="48" height="48"
                            alt="Avatar de <?= esc($post['author_name']) ?>">
                        <div>
                            <h6 class="mb-0 fw-bold text-primary"><?= esc($post['author_name']) ?></h6>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small>
                        </div>
                    </a>
                </div>

                <h5 id="post-title-<?= esc($post['slug']) ?>" class="card-title fw-semibold text-truncate">
                    <?= esc($post['title']) ?>
                </h5>

                <p class="card-text text-secondary mb-3" style="min-height: 3.6em; /* 2 lines approx */">
                    <?php
                    $content = strip_tags($post['html_content']);
                    // character_limiter, fixar para não cometer o mesmo erro.
                    if (mb_strlen($content) > 150) {
                        $content = mb_substr($content, 0, 147) . '...';
                    }
                    echo esc($content);
                    ?>
                </p>

                <a href="/blog/<?= esc($post['slug']) ?>" class="btn btn-sm btn-outline-primary" aria-label="Ler a publicação <?= esc($post['title']) ?>">
                    <i class="bi bi-book"></i> Ler completo
                </a>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>