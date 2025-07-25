<?php if (empty($posts)) : ?>
    <div class="alert alert-info text-center py-4">
        <i class="bi bi-info-circle me-2"></i>Nenhum post encontrado. Seja o primeiro a publicar!
    </div>
<?php else : ?>
    <?php foreach ($posts as $post) : ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-grow-1">
                        <strong class="d-block"><?= esc($post['author_name']) ?></strong>
                        <small class="text-muted">
                            <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                        </small>
                    </div>
                </div>
                
                <h5 class="card-title"><?= esc($post['title']) ?></h5>
                
                <div class="card-text">
                    <?= esc(character_limiter(strip_tags($post['html_content']), 150)) ?>
                </div>
                
                <a href="/blog/<?= esc($post['slug']) ?>" class="btn btn-outline-primary mt-2">
                    Ler completo
                </a>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>