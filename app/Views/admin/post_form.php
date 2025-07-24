<?= $this->extend('partials/layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
  <h2><?= esc($title) ?></h2>

  <?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertErrors">
      <ul>
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach ?>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif ?>

  <form action="<?= esc($action) ?>" method="post" id="postForm" novalidate>
    <div class="mb-3">
      <label for="title" class="form-label">Título</label>
      <input type="text" class="form-control" id="title" name="title" 
             value="<?= old('title', $post['title'] ?? '') ?>" required minlength="3">
      <div class="invalid-feedback">Título deve ter no mínimo 3 caracteres.</div>
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Conteúdo</label>
      <textarea class="form-control" id="content" name="content" rows="6" required minlength="10"><?= old('content', $post['html_content'] ?? '') ?></textarea>
      <div class="invalid-feedback">Conteúdo deve ter no mínimo 10 caracteres.</div>
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select id="status" name="status" class="form-select" required>
        <?php $status = old('status', $post['status'] ?? 'draft'); ?>
        <option value="">Selecione um status</option>
        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Rascunho</option>
        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Publicado</option>
        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Arquivado</option>
      </select>
      <div class="invalid-feedback">Por favor, selecione um status válido.</div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="/admin/posts" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<script>
  (() => {
    'use strict';
    const form = document.querySelector('#postForm');
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });

    // Esconder alertas depois de 5 segundos
    const alertErrors = document.getElementById('alertErrors');
    if(alertErrors) {
      setTimeout(() => {
        alertErrors.classList.remove('show');
        alertErrors.classList.add('hide');
      }, 5000);
    }
  })();
</script>

<?= $this->endSection() ?>
