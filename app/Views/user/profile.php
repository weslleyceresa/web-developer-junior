<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<style>
  .avatar-lg {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #e9ecef;
    box-shadow: 0 0 0.5rem rgba(0,0,0,0.05);
  }

  .profile-card {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.05);
  }

  .post-card {
    border: none;
    border-radius: 0.75rem;
  }

  .post-card:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
  }

  .btn-outline-primary {
    font-weight: 500;
  }
</style>

<div class="container mt-4">
  <!-- Cabeçalho do perfil -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Meu Perfil</h2>
    <a href="/user/edit" class="btn btn-outline-primary">
      <i class="bi bi-pencil me-1"></i> Editar Perfil
    </a>
  </div>

  <!-- Informações do usuário -->
  <div class="profile-card mb-5">
    <div class="d-flex align-items-center">
      <img src="<?= base_url($user['avatar_path'] ?: 'uploads/avatars/default-avatar.png') ?>" alt="Avatar"
           class="avatar-lg me-4">
      <div>
        <h4 class="mb-1"><?= esc($user['name']) ?> <small class="text-muted">@<?= esc($user['username']) ?></small></h4>
        <p class="text-muted mb-0"><?= esc($user['bio']) ?: 'Sem biografia disponível.' ?></p>
      </div>
    </div>
  </div>

  <!-- Lista de postagens -->
  <h4 class="mb-3">Minhas Postagens</h4>

  <?php if (empty($posts)): ?>
    <div class="alert alert-info text-center py-4">
      <i class="bi bi-info-circle me-2"></i>Você ainda não escreveu nenhuma postagem.
    </div>
  <?php else: ?>
    <?php foreach ($posts as $post): ?>
      <div class="card post-card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= esc($post['title']) ?></h5>
          <p class="text-muted mb-2"><i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></p>
          <a href="/blog/<?= esc($post['slug']) ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye me-1"></i> Ver post
          </a>
        </div>
      </div>
    <?php endforeach ?>
  <?php endif ?>
</div>

<?= $this->endSection() ?>
