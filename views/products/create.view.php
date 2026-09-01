<form method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="page-header">
        <div class="page-header-title">
            <a
                class="btn btn-ghost btn-icon"
                href="?route=products/index"
                title="Voltar para Produtos"
                aria-label="Voltar para Produtos">

                <?php echo icon('arrow-left'); ?>

            </a>
            <div class="page-header-left">
                <h1 class="page-title">
                    Adicionar Produto
                </h1>

                <p class="page-description">
                    Preencha as informações para cadastrar um novo produto no catálogo.
                </p>

            </div>
        </div>
    </div>

    <div class="form-panel">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">

                <?php echo icon('alert-circle'); ?>

                <ul class="alert-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="card-header-content">
                    <div class="card-title">
                        Informações Gerais
                    </div>

                    <div class="card-subtitle">
                        Dados principais e descrição do produto.
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-field form-field-full">
                        <label class="form-label" for="name">
                            Nome do Produto
                        </label>

                        <input
                            class="form-input"
                            id="name"
                            type="text"
                            name="name"
                            value="<?php echo e($_POST['name'] ?? ''); ?>"
                            placeholder="Ex: Saia de Malha com Bolso">
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="category">
                            Categoria
                        </label>

                        <select
                            class="form-select"
                            id="category"
                            name="category_id">

                            <?php $selectedCategory = $_POST['category_id'] ?? ''; ?>

                            <option value="">
                                Categoria do Produto
                            </option>

                            <?php foreach ($categories as $category): ?>
                                <option
                                    value="<?php echo e($category->id); ?>"
                                    <?php echo $category->id == $selectedCategory ? 'selected' : ''; ?>>

                                    <?php echo e($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="tag">
                            Tag
                        </label>

                        <input
                            class="form-input"
                            id="tag"
                            type="text"
                            name="tag"
                            value="<?php echo e($_POST['tag'] ?? ''); ?>"
                            placeholder="Ex: Rendada">
                    </div>
                    <div class="form-field form-field-full">
                        <label class="form-label" for="description">
                            Descrição
                        </label>

                        <textarea
                            class="form-textarea"
                            id="description"
                            name="description"
                            placeholder="Descreva o produto..."><?php echo e($_POST['description'] ?? ''); ?></textarea>

                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-header-content">
                    <div class="card-title">
                        Preço e Estoque
                    </div>

                    <div class="card-subtitle">
                        Valores de venda e controle de inventário.
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label class="form-label" for="price">
                            Preço (R$)
                        </label>

                        <input
                            class="form-input"
                            id="price"
                            type="number"
                            name="price"
                            min="0"
                            step="0.01"
                            value="<?php echo e($_POST['price'] ?? ''); ?>"
                            placeholder="0,00">
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="stock">
                            Estoque
                        </label>

                        <input
                            class="form-input"
                            id="stock"
                            type="number"
                            name="stock"
                            min="0"
                            step="1"
                            value="<?php echo e($_POST['stock'] ?? ''); ?>"
                            placeholder="0">
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header-content">
                    <div class="card-title">
                        Imagem do Produto
                    </div>

                    <div class="card-subtitle">
                        Imagem utilizada para identificar o produto.
                    </div>
                </div>
            </div>

            <div class="card-body">
                <label class="image-upload" for="product-image-input">

                    <img id="image-preview" class="image-preview"
                        src="<?php echo !empty($tempPhoto) ? 'uploads/tmp/' . e($tempPhoto) : ''; ?>"
                        alt="Pré-visualização"
                        style="<?php echo !empty($tempPhoto) ? '' : 'display:none;'; ?>">

                    <svg
                        id="image-upload-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        style="<?php echo !empty($tempPhoto)
                                    ? 'display:none;'
                                    : ''; ?>">

                        <path d="M4 16l4.6-4.6a2 2 0 012.8 0L16 16" />
                        <path d="M14 14l1.6-1.6a2 2 0 012.8 0L20 14" />
                        <rect x="3" y="4" width="18" height="16" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                    </svg>

                    <div
                        class="image-upload-text"
                        id="image-upload-text"
                        style="<?php echo !empty($tempPhoto)
                                    ? 'display:none;'
                                    : ''; ?>">

                        <div class="image-upload-title">
                            Clique para enviar uma imagem
                        </div>

                        <div class="image-upload-hint">
                            PNG ou JPG, até 5 MB
                        </div>

                    </div>
                    <input
                        id="product-image-input"
                        type="file"
                        name="photo"
                        accept="image/png,image/jpeg"
                        hidden>
                </label>
                <input
                    type="hidden"
                    name="temp_photo"
                    value="<?php echo !empty($tempPhoto) ? e($tempPhoto) : ''; ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2.5">

                    <polyline points="20 6 9 17 4 12" />
                </svg>

                Salvar Produto
            </button>
            <a class="btn btn-ghost" href="?route=products/index">Cancelar</a>
        </div>
    </div>
</form>