<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <!-- Coluna lateral esquerda: Perfil, botão de escrever e rascunhos -->
        <div class="col-md-3 d-none d-md-block">
            <div class="card mb-3 text-center">
                <div class="card-body">
                    <a href="/user/profile">
                        <img src="<?= base_url(session()->get('avatar_path') ?: 'uploads/avatars/default-avatar.png') ?>"
                            class="avatar avatar-md mb-2" alt="Avatar do usuário">
                    </a>
                    <h5>
                        <a href="/user/profile" class="text-decoration-none text-dark">
                            <?= esc(session()->get('user_name')) ?>
                        </a>
                    </h5>
                    <p class="text-muted">@<?= esc(session()->get('username')) ?></p>
                    <p class="small text-muted">Bem-vindo ao Blog PME!</p>
                </div>
            </div>

            <!-- Botão de escrever publicação na lateral -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body text-center">
                    <button class="btn btn-primary w-100" onclick="window.location.href='/blog/create'">
                        <i class="bi bi-pencil-square me-2"></i>Escrever uma publicação
                    </button>
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

        <!-- Coluna central: Busca, conteúdo dinâmico e feed -->
        <div class="col-md-6">
            <!-- Formulário de busca no topo da coluna central -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form action="<?= base_url('blog/search') ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q"
                                placeholder="Buscar publicações..."
                                value="<?= esc($searchQuery ?? '') ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Conteúdo dinâmico (mapa, dicas etc.) -->
            <div id="dynamicContent"></div>

            <div id="blogFeed">
                <!-- Mensagem de busca -->
                <?php if (!empty($searchQuery)): ?>
                    <div class="alert alert-light mb-4">
                        Resultados para: <strong><?= esc($searchQuery) ?></strong>
                    </div>
                <?php endif; ?>
                
                <div id="postsContainer">
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
                        <li><button class="btn btn-link p-0" onclick="showContent('business')">📰 Assuntos empresariais</button></li>
                        <li><button class="btn btn-link p-0" onclick="showContent('management')">📊 Dicas de gestão</button></li>
                        <li><button class="btn btn-link p-0" onclick="showContent('presidente')">📍 Presidente Prudente</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scroll infinito + conteúdo dinâmico -->
<script>
    let page = <?= !empty($initialPosts) ? 2 : 1 ?>;
    let loading = false;
    let allLoaded = <?= empty($initialPosts) ? 'true' : 'false' ?>;
    let isSearch = <?= !empty($searchQuery) ? 'true' : 'false' ?>; // Verifica se estamos em uma busca

    function loadPosts() {
        // Se estivermos em uma busca, não carregamos mais posts via scroll
        if (isSearch) {
            return;
        }

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
        // Se não for uma busca, ativamos o scroll infinito
        if (!isSearch && page === 1) {
            loadPosts();
        }

        // Só configuramos o scroll se não for uma busca
        if (!isSearch) {
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
                    loadPosts();
                }
            });
        }

        // Adiciona o spinner no botão de busca ao submeter o formulário
        $('form[action="<?= base_url('blog/search') ?>"]').submit(function() {
            const button = $(this).find('button[type="submit"]');
            button.prop('disabled', true);
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...');
        });
    });

    // Conteúdo dinâmico ao clicar nas preferências
    function showContent(type) {
        const contentDiv = $('#dynamicContent');
        contentDiv.empty().show(); // Garante que a div está visível

        let content = `
        <div class="d-flex justify-content-end mb-2">
            <button class="btn btn-sm btn-outline-secondary" onclick="hideContent()">❌ Fechar conteúdo</button>
        </div>
    `;

        if (type === 'business') {
            content += `
            <div class="card">
                <div class="card-body">
                    <h5>Assuntos Empresariais</h5>
                    <p>Fique por dentro de temas como empreendedorismo, economia, inovação e estratégias para pequenas e médias empresas.</p>
                </div>
            </div>
        `;
        } else if (type === 'management') {
            content += `
            <div class="card">
                <div class="card-body">
                    <h5>Dicas de Gestão</h5>
                    <ul>
                        <li>✅ Defina metas claras e mensuráveis.</li>
                        <li>📈 Acompanhe indicadores de desempenho.</li>
                        <li>👥 Valorize a comunicação com a equipe.</li>
                    </ul>
                </div>
            </div>
        `;
        } else if (type === 'presidente') {
            content += `
            <div class="card">
                <div class="card-body">
                    <h5>Presidente Prudente - Localização</h5>
                    <div style="width: 100%; height: 300px;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3674.556734356483!2d-51.393308184411175!3d-22.1211000451817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x949a5b8e9f4b48af%3A0x25f3c1309aebd380!2sPresidente%20Prudente%2C%20SP!5e0!3m2!1spt-BR!2sbr!4v1689793024000!5m2!1spt-BR!2sbr" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        `;
        }

        contentDiv.html(content);
    }

    function hideContent() {
        $('#dynamicContent').fadeOut().empty();
    }
</script>

<?= $this->endSection() ?>