<?= $this->extend('partials/layout_private') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4"><?= isset($post['id']) ? 'Editar Post' : 'Novo Post' ?></h2>

    <form action="<?= isset($post['id']) ? '/admin/posts/update/' . $post['id'] : '/admin/posts/create' ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $post['title'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug (URL amigável)</label>
            <input type="text" class="form-control" id="slug" name="slug" value="<?= old('slug', $post['slug'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="content" name="content" rows="6" required><?= old('content', $post['content'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= isset($post['id']) ? 'Atualizar' : 'Publicar' ?>
        </button>

        <a href="/admin/posts" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>
