<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Posts</h2>
    <a href="/admin/posts/new" class="btn btn-success">Novo Post</a>
  </div>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertSuccess">
      <?= esc(session()->getFlashdata('success')) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif ?>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Título</th>
        <th>Status</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($posts as $post): ?>
        <tr>
          <td><?= esc($post['title']) ?></td>
          <td>
            <?php 
              switch($post['status']) {
                case 'published': echo '<span class="badge bg-success">Publicado</span>'; break;
                case 'draft': echo '<span class="badge bg-secondary">Rascunho</span>'; break;
                case 'archived': echo '<span class="badge bg-warning text-dark">Arquivado</span>'; break;
                default: echo '<span class="badge bg-info">Desconhecido</span>';
              }
            ?>
          </td>
          <td><?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></td>
          <td><?= $post['updated_at'] ? date('d/m/Y H:i', strtotime($post['updated_at'])) : '-' ?></td>
          <td>
            <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="/admin/posts/delete/<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<script>
  // Esconder alerta de sucesso após 5 segundos
  const alertSuccess = document.getElementById('alertSuccess');
  if(alertSuccess) {
    setTimeout(() => {
      alertSuccess.classList.remove('show');
      alertSuccess.classList.add('hide');
    }, 5000);
  }
</script>

<?= $this->endSection() ?>
