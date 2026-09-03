<form method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <input
        type="hidden"
        name="product_id"
        value="<?php echo e($product->id); ?>">

    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-title">
                <a
                    class="btn btn-ghost btn-icon"
                    href="?route=products/index"
                    title="Voltar para Produtos"
                    aria-label="Voltar para Produtos">

                    <?php echo icon('arrow-left'); ?>

                </a>
                <div>
                    <h1 class="page-title">
                        Editar Produto
                    </h1>

                    <p class="page-description">
                        Preencha as informações para editar o produto.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-panel form-panel-wide">
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
                        Nome, categoria e identificação do produto
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
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo e($_POST['name'] ?? $product->name); ?>"
                            required>
                    </div>

                    <div class="form-field">
                        <label
                            class="form-label"
                            for="category">
                            Categoria
                        </label>

                        <select
                            class="form-select"
                            id="category"
                            name="category_id"
                            required>

                            <?php $selectedCategory = $_POST['category_id'] ?? $product->category_id; ?>

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
                            type="text"
                            id="tag"
                            name="tag"
                            value="<?php echo e($_POST['tag'] ?? $product->tag); ?>">
                    </div>

                    <div class="form-field form-field-full">
                        <label class="form-label" for="description">
                            Descrição
                        </label>

                        <textarea
                            class="form-textarea"
                            id="description"
                            name="description"><?php echo e($_POST['description'] ?? $product->description); ?></textarea>
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
                        Valores de venda e controle de inventário
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
                            type="number"
                            id="price"
                            name="price"
                            min="0"
                            step="0.01"
                            value="<?php echo e($_POST['price'] ?? $product->price); ?>"
                            required>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="stock">
                            Estoque
                        </label>

                        <input
                            class="form-input"
                            type="number"
                            id="stock"
                            name="stock"
                            min="0"
                            step="1"
                            value="<?php echo e($_POST['stock'] ?? $product->stock); ?>">
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
                        Foto utilizada para identificar o produto
                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php
                // Prioriza a foto temporária, caso não haja, cai para a foto já salva do produto.
                $hasPreview = !empty($tempPhoto) || !empty($product->photo);
                $previewSrc = '';

                if (!empty($tempPhoto)) {
                    $previewSrc = 'uploads/tmp/' . $tempPhoto;
                } elseif (!empty($product->photo)) {
                    $previewSrc = 'uploads/products/' . $product->photo;
                }
                ?>
                <label class="image-upload" for="product-image-input">
                    <img id="image-preview" class="image-preview"
                        src="<?php echo e($previewSrc); ?>"
                        alt="Pré-visualização"
                        style="<?php echo $hasPreview ? '' : 'display:none;'; ?>">

                    <svg
                        id="image-upload-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        style="<?php echo $hasPreview ? 'display:none;' : ''; ?>">

                        <path d="M4 16l4.6-4.6a2 2 0 012.8 0L16 16" />
                        <path d="M14 14l1.6-1.6a2 2 0 012.8 0L20 14" />
                        <rect x="3" y="4" width="18" height="16" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                    </svg>

                    <div
                        class="image-upload-text"
                        id="image-upload-text"
                        style="<?php echo $hasPreview ? 'display:none;' : ''; ?>">

                        <div class="image-upload-title">
                            Clique para enviar uma imagem
                        </div>

                        <div class="image-upload-hint">
                            PNG ou JPG, até 5MB
                        </div>
                    </div>

                    <input
                        id="product-image-input"
                        type="file"
                        name="photo"
                        accept="image/png,image/jpeg"
                        style="display:none">
                </label>

                <input
                    type="hidden"
                    name="temp_photo"
                    value="<?php echo !empty($tempPhoto) ? e($tempPhoto) : ''; ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">

                <?php echo icon('check'); ?>

                Salvar Alterações
            </button>
            <a
                class="btn btn-ghost"
                href="?route=products/index">
                Cancelar
            </a>
        </div>
    </div>
</form>