<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<style>
    /* Estilos para o avatar do usuário */
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #0d6efd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.4);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .user-avatar:hover {
        transform: scale(1.1);
    }

    /* Card dos rascunhos com scroll se muitos */
    #draftsCard {
        max-height: 280px;
        overflow-y: auto;
    }

    /* Cards de posts */
    .post-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .1);
        transition: box-shadow 0.3s ease;
        cursor: pointer;
    }

    .post-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, .15);
    }

    /* Botão na sidebar */
    .btn-write {
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-write:hover {
        background-color: #0b5ed7;
    }

    /* Lista de preferências */
    .preferences-list button {
        font-size: 0.95rem;
        color: #0d6efd;
        text-decoration: none;
        padding: 0;
    }

    .preferences-list button:hover {
        text-decoration: underline;
        color: #0a58ca;
        cursor: pointer;
    }

    /* Form de busca */
    form input.form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13, 110, 253, .4);
    }

    /* Conteúdo dinâmico */
    #dynamicContent>.card {
        animation: fadeIn 0.4s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container mt-5">
    <div class="row gx-4">
        <!-- Coluna lateral esquerda -->
        <div class="col-md-3 d-none d-md-block">
            <div class="card mb-4 text-center shadow-sm">
                <div class="card-body">
                    <a href="/user/profile" title="Perfil do usuário">
                        <img src="<?= base_url(session()->get('avatar_path') ?: 'uploads/avatars/default-avatar.png') ?>" alt="Avatar do usuário" class="user-avatar mb-3">
                    </a>
                    <h5>
                        <a href="/user/profile" class="text-decoration-none text-dark">
                            <?= esc(session()->get('user_name')) ?>
                        </a>
                    </h5>
                    <p class="text-muted mb-1">@<?= esc(session()->get('username')) ?></p>
                    <p class="small text-secondary fst-italic">Bem-vindo ao Blog PME!</p>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <button class="btn btn-primary btn-write w-100" onclick="window.location.href='/blog/create'">
                        <i class="bi bi-pencil-square me-2"></i> Escrever uma publicação
                    </button>
                </div>
            </div>

            <?php $drafts = session()->get('drafts') ?? []; ?>
            <?php if (!empty($drafts)): ?>
                <div class="card shadow-sm" id="draftsCard">
                    <div class="card-body">
                        <h6 class="mb-3">Rascunhos</h6>
                        <ul class="list-group list-group-flush small">
                            <?php foreach ($drafts as $i => $draft): ?>
                                <li class="list-group-item">
                                    <strong><?= esc($draft['title'] ?: '(Sem título)') ?></strong>
                                    <span class="text-muted d-block mt-1">
                                        <?php
                                        $content = strip_tags($draft['content'] ?? '');
                                        // word_limiter.
                                        $words = explode(' ', $content);
                                        $limited = implode(' ', array_slice($words, 0, 10));
                                        if (count($words) > 10) $limited .= '...';
                                        echo esc($limited);
                                        ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Coluna central -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form action="<?= base_url('blog/search') ?>" method="get" autocomplete="off">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" placeholder="Buscar publicações..." value="<?= esc($searchQuery ?? '') ?>" aria-label="Buscar publicações">
                            <button class="btn btn-primary" type="submit" aria-label="Buscar">
                                <i class="bi bi-search"></i>
                                <span class="visually-hidden">Buscar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="dynamicContent"></div>

            <div id="blogFeed">
                <?php if (!empty($searchQuery)): ?>
                    <div class="alert alert-light mb-4">
                        Resultados para: <strong><?= esc($searchQuery) ?></strong>
                    </div>
                <?php endif; ?>

                <div id="postsContainer">
                    <?= view('blog/_posts', ['posts' => $initialPosts]) ?>
                </div>

                <div id="loading" class="text-center my-4 d-none">
                    <div class="spinner-border text-primary" role="status" aria-label="Carregando posts"></div>
                </div>

                <p id="endMessage" class="text-center text-muted d-none">Você chegou ao fim.</p>
            </div>
        </div>

        <!-- Coluna lateral direita -->
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

<script>
    let page = <?= !empty($initialPosts) ? 2 : 1 ?>;
    let loading = false;
    let allLoaded = <?= empty($initialPosts) ? 'true' : 'false' ?>;
    let isSearch = <?= !empty($searchQuery) ? 'true' : 'false' ?>;

    function loadPosts() {
        if (isSearch || loading || allLoaded) return;
        loading = true;
        $('#loading').removeClass('d-none');

        $.get('/blog/load-more?page=' + page)
            .done(function(data) {
                if ($.trim(data) === '') {
                    allLoaded = true;
                    $('#endMessage').removeClass('d-none');
                } else {
                    $('#postsContainer').append(data);
                    page++;
                }
            })
            .fail(function() {
                console.error('Erro ao carregar posts');
            })
            .always(function() {
                $('#loading').addClass('d-none');
                loading = false;
            });
    }

    $(function() {
        if (!isSearch && page === 1) {
            loadPosts();
        }

        if (!isSearch) {
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
                    loadPosts();
                }
            });
        }

        $('form[action="<?= base_url('blog/search') ?>"]').submit(function() {
            const btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true);
            btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...');
        });
    });

    function showContent(type) {
        const contentDiv = $('#dynamicContent');
        contentDiv.empty().show();

        let content = `
            <div class="d-flex justify-content-end mb-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="hideContent()" aria-label="Fechar conteúdo">❌ Fechar conteúdo</button>
            </div>`;

        if (type === 'business') {
            content += `
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Assuntos Empresariais</h5>
                        <p>Fique por dentro de temas como empreendedorismo, economia, inovação e estratégias para pequenas e médias empresas.</p>
                    </div>
                </div>`;
        } else if (type === 'management') {
            content += `
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Dicas de Gestão</h5>
                        <ul>
                            <li>✅ Defina metas claras e mensuráveis.</li>
                            <li>📈 Acompanhe indicadores de desempenho.</li>
                            <li>👥 Valorize a comunicação com a equipe.</li>
                        </ul>
                    </div>
                </div>`;
        } else if (type === 'presidente') {
            content += `
                <div class="card shadow-sm">
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
                </div>`;
        }

        contentDiv.html(content);
    }

    function hideContent() {
        $('#dynamicContent').fadeOut().empty();
    }
</script>

<?= $this->endSection() ?>