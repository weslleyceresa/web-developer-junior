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

            <?php
            $avatarUrl = base_url($user['avatar_path'] ?: 'uploads/avatars/default-avatar.png');
            ?>
            <img src="<?= $avatarUrl ?>" class="mt-2 avatar avatar-sm">

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
        </div>

        <button type="submit" class="btn btn-success">Salvar alterações</button>
        <a href="/user/profile" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<?= $this->endSection() ?>