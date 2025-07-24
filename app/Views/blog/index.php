<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container">
  <div class="row">
    <!-- Coluna Esquerda: Perfil -->
    <div class="col-md-3 d-none d-md-block">
      <div class="card mb-4">
        <div class="card-body text-center">
          <h5 class="card-title"><?= esc(session()->get('user_name')) ?></h5>
          <p class="text-muted">@<?= esc(session()->get('username')) ?></p>
          <hr>
          <p class="small text-muted">Bem-vindo ao Blog PME!</p>
        </div>
      </div>
    </div>

    <!-- Coluna Central: Publicar + Feed -->
    <div class="col-md-6">
      <!-- Início Publicação -->
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h6 class="mb-3">Iniciar uma nova publicação</h6>
          <a href="/admin/posts/create" class="btn btn-sm btn-outline-primary w-100">Escrever Post</a>
        </div>
      </div>

      <!-- Feed -->
      <div id="postsContainer"></div>
      <div id="loading" class="text-center my-4 d-none">
        <div class="spinner-border text-primary" role="status"></div>
      </div>
      <p id="endMessage" class="text-center text-muted d-none">Você chegou ao fim.</p>
    </div>

    <!-- Coluna Direita: Preferências -->
    <div class="col-md-3 d-none d-md-block">
      <div class="card mb-4">
        <div class="card-body">
          <h6 class="card-title">Suas Preferências</h6>
          <ul class="list-unstyled small text-muted">
            <li>📰 Assuntos empresariais</li>
            <li>📊 Dicas de gestão</li>
            <li>📍 Presidente Prudente</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Feed Script -->
<script>
  let page = 1;
  let loading = false;
  let allLoaded = false;

  function loadPosts() {
    if (loading || allLoaded) return;
    loading = true;
    $('#loading').removeClass('d-none');

    $.get('/blog/load-more?page=' + page, function(data) {
      if ($.trim(data) === '') {
        allLoaded = true;
        $('#endMessage').removeClass('d-none');
      } else {
        $('#postsContainer').append(data);
        page++;
      }
    }).always(() => {
      $('#loading').addClass('d-none');
      loading = false;
    });
  }

  $(document).ready(function () {
    loadPosts();
    $(window).scroll(function () {
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
        loadPosts();
      }
    });
  });
</script>

<?= $this->endSection() ?>
