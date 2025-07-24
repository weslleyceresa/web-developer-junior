<?php foreach ($posts as $post): ?>
  <div class="card mb-4">
    <div class="card-body">
      <h4 class="card-title"><?= esc($post['title']) ?></h4>
      <p class="card-text"><?= esc(character_limiter(strip_tags($post['html_content']), 150)) ?></p>
      <p class="text-muted small mb-0">Publicado em <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
    </div>
  </div>
<?php endforeach ?>
