<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-3">Nova Publicação</h3>
    <form id="postForm" method="POST" action="/blog/create" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Título -->
        <div class="mb-3">
            <input type="text" class="form-control" name="title" id="postTitle" placeholder="Título da publicação" required>
        </div>

        <!-- Conteúdo -->
        <div class="mb-3">
            <textarea class="form-control" name="html_content" id="postContent" rows="8" placeholder="Escreva aqui..." required></textarea>
        </div>

        <!-- Upload da imagem -->
        <div class="mb-3">
            <label for="postImage" class="form-label">Imagem de capa (opcional)</label>
            <input class="form-control" type="file" name="post_image" id="postImage" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Publicar</button>
        <button type="button" id="saveDraft" class="btn btn-secondary ms-2">Salvar como rascunho</button>
        <a href="/blog" class="btn btn-link ms-3" id="backToFeed">Voltar para o feed</a>
    </form>
</div>

<script>
    let isDirty = false;

    $('#postTitle, #postContent').on('input', () => {
        isDirty = true;
    });

    $('#postForm').submit(function(e) {
        // Validação básica antes do envio
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        isDirty = false;
    });

    $('#saveDraft').click(function() {
        $.post('/blog/save-draft', {
            title: $('#postTitle').val(),
            html_content: $('#postContent').val(),
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        }, function() {
            isDirty = false;
            alert('Rascunho salvo com sucesso!');
            window.location.href = '/blog';
        });
    });

    $('#backToFeed').click(function(e) {
        if (isDirty) {
            e.preventDefault();
            if (confirm('Deseja salvar como rascunho antes de sair?')) {
                $('#saveDraft').click();
            } else {
                window.location.href = '/blog';
            }
        }
    });

    window.onbeforeunload = function() {
        if (isDirty) {
            return 'Você tem alterações não salvas. Deseja sair mesmo assim?';
        }
    };
</script>

<?= $this->endSection() ?>