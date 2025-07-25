<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2>Editar Perfil</h2>
  <form action="/user/update" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
      <label>Nome</label>
      <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Telefone</label>
      <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>">
    </div>
    <div class="mb-3">
      <label>Biografia</label>
      <textarea name="bio" class="form-control" rows="3"><?= esc($user['bio']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Avatar</label>
      <input type="file" name="avatar" class="form-control">
      <?php if (!empty($user['avatar_path'])): ?>
        <img src="<?= esc($user['avatar_path']) ?>" width="80" class="mt-2 rounded-circle">
      <?php endif ?>
    </div>
    <button type="submit" class="btn btn-success">Salvar alterações</button>
    <a href="/user/profile" class="btn btn-secondary ms-2">Cancelar</a>
  </form>
</div>

<?= $this->endSection() ?>
