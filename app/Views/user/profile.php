<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Meu Perfil</h2>
    <a href="/user/edit" class="btn btn-outline-primary">Editar Perfil</a>
  </div>
  <div class="card mb-4 p-4">
    <div class="d-flex align-items-center">
      <img src="<?= esc($user['avatar_path'] ?? '/default-avatar.png') ?>" class="rounded-circle me-3" width="80" height="80">
      <div>
        <h5><?= esc($user['name']) ?> <small class="text-muted">@<?= esc($user['username']) ?></small></h5>
        <p class="text-muted mb-0"><?= esc($user['bio']) ?></p>
      </div>
    </div>
  </div>

  <h4 class="mb-3">Minhas Postagens</h4>
  <?php if (empty($posts)): ?>
    <div class="alert alert-info">Você ainda não escreveu nenhuma postagem.</div>
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
