<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <!-- Coluna lateral esquerda: Perfil e rascunhos -->
        <div class="col-md-3 d-none d-md-block">
            <div class="card mb-3 text-center">
                <div class="card-body">
                    <h5><?= esc(session()->get('user_name')) ?></h5>
                    <p class="text-muted">@<?= esc(session()->get('username')) ?></p>
                    <p class="small text-muted">Bem-vindo ao Blog PME!</p>
                </div>
            </div>

            <?php $drafts = session()->get('drafts') ?? []; ?>
            <?php if (!empty($drafts)): ?>
                <div class="card" id="draftsCard">
                    <div class="card-body">
                        <h6>Rascunhos</h6>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($drafts as $i => $draft): ?>
                                <li class="list-group-item">
                                    <strong><?= esc($draft['title'] ?: '(Sem título)') ?></strong>
                                    <span class="text-muted d-block small"><?= word_limiter(strip_tags($draft['content']), 10) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Coluna central: Feed e botão de postagem -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <button class="btn btn-primary" onclick="window.location.href='/blog/create'">
                        Escrever uma publicação
                    </button>
                </div>
            </div>

            <div id="blogFeed">
                <div id="postsContainer">
                    <!-- Posts iniciais carregados diretamente do controller -->
                    <?= view('blog/_posts', ['posts' => $initialPosts]) ?>
                </div>
                <div id="loading" class="text-center my-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <p id="endMessage" class="text-center text-muted d-none">Você chegou ao fim.</p>
            </div>
        </div>

        <!-- Coluna lateral direita: Preferências -->
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

<!-- Scroll infinito -->
<script>
    let page = <?= !empty($initialPosts) ? 2 : 1 ?>; // Começa na página 2 se houver posts iniciais
    let loading = false;
    let allLoaded = <?= empty($initialPosts) ? 'true' : 'false' ?>;

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
        }).fail(function() {
            console.error('Erro ao carregar posts');
        }).always(function() {
            $('#loading').addClass('d-none');
            loading = false;
        });
    }

    $(document).ready(function() {
        // Se não há posts iniciais, carrega a primeira página
        if (page === 1) {
            loadPosts();
        }

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
                loadPosts();
            }
        });
    });
</script>

<?= $this->endSection() ?>