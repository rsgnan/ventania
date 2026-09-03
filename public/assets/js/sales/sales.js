// Produtos da venda
const saleItems = typeof existingSaleItems !== 'undefined'
    ? existingSaleItems.map(function (item) {

        const product = products.find(function (product) {
            return Number(product.id) === Number(item.product_id);
        });

        return {
            id: Number(item.product_id),
            name: item.product_name,
            price: Number(item.unit_price),
            stock: Number(product ? product.stock : 0) + Number(item.quantity),
            quantity: Number(item.quantity)
        };
    })
    : [];

const searchInput = document.getElementById('searchInput');
const autocompleteResults = document.getElementById('autocompleteResults');

// Buscar por produto
searchInput.addEventListener('input', function () {
    const search = this.value.trim().toLowerCase();

    autocompleteResults.innerHTML = '';

    if (search === '') {
        autocompleteResults.style.display = 'none';
        return;
    }

    const results = products.filter(function (product) {
        return product.name.toLowerCase().includes(search);
    });

    if (results.length === 0) {
        autocompleteResults.innerHTML = `
            <div class="autocomplete-empty">
                Nenhum produto encontrado.
            </div>
        `;

        autocompleteResults.style.display = 'block';
        return;
    }

    results.forEach(function (product) {

        const item = document.createElement('div');

        item.className = 'autocomplete-item';
        item.addEventListener('click', function () {
            addProduct(product);
        });

        item.innerHTML = `
            <div>
                <strong>${product.name}</strong>
                <small>Estoque: ${product.stock}</small>
            </div>
            <span>R$ ${Number(product.price).toFixed(2).replace('.', ',')}</span>
            `;

        autocompleteResults.appendChild(item);
    });

    autocompleteResults.style.display = 'block';
});

// Adicionar produto
function addProduct(product) {
    const productId = Number(product.id);
    const productStock = Number(product.stock);

    const existingItem = saleItems.find(function (item) {
        return item.id === productId;
    });

    if (existingItem) {
        if (existingItem.quantity < existingItem.stock) {
            existingItem.quantity++;
        }
    } else {
        if (productStock <= 0) {
            return;
        }

        saleItems.push({
            id: productId,
            name: product.name,
            price: Number(product.price),
            stock: productStock,
            quantity: 1
        });
    }

    renderItems();
    updateSummary();
    updateSaleItemsInput();

    searchInput.value = '';
    autocompleteResults.innerHTML = '';
    autocompleteResults.style.display = 'none';
}

// Renderizar produtos
function renderItems() {
    const itemsBody = document.getElementById('itemsBody');

    itemsBody.innerHTML = '';

    if (saleItems.length === 0) {
        itemsBody.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="sale-empty">
                        <div class="sale-empty-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                
                                <circle cx="9" cy="20" r="1" />
                                <circle cx="19" cy="20" r="1" />
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6" />
                            </svg>
                        </div>
                        
                        <strong>
                            Nenhum produto adicionado.
                        </strong>
                        
                        <span>
                            Busque um produto para começar.
                        </span>
                    </div>
                </td>
            </tr>
        `;

        return;

    }

    saleItems.forEach(function (item) {
        const subtotal = item.price * item.quantity;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${item.name}</td>
            
            <td>
                <input
                    type="number"
                    value="${item.quantity}"
                    min="1"
                    max="${item.stock}"
                    class="item-quantity"
                >
            </td>

            <td>
                R$ ${item.price.toFixed(2).replace('.', ',')}
            </td>

            <td>
                R$ ${subtotal.toFixed(2).replace('.', ',')}
            </td>

            <td>
                <button 
                    type="button" 
                    class="btn-remove">

                    Remover
                </button>
            </td>
            `;

        itemsBody.appendChild(row);

        const quantityInput = row.querySelector('.item-quantity');

        quantityInput.addEventListener('change', function () {
            let quantity = parseInt(this.value, 10);

            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }

            if (quantity > item.stock) {
                quantity = item.stock;
            }

            item.quantity = quantity;

            renderItems();
            updateSummary();
            updateSaleItemsInput();
        });

        // Remover produto

        const removeButton = row.querySelector('.btn-remove');

        removeButton.addEventListener('click', function () {
            const index = saleItems.findIndex(function (saleItem) {
                return saleItem.id === item.id;
            });

            if (index !== -1) {
                saleItems.splice(index, 1);
            }

            renderItems();
            updateSummary();
            updateSaleItemsInput();
        });

    });
}

// Resumo da venda
function updateSummary() {
    const sumDistinct = document.getElementById('sumDistinct');
    const sumQty = document.getElementById('sumQty');
    const sumSubtotal = document.getElementById('sumSubtotal');
    const sumTotal = document.getElementById('sumTotal');
    const discountAmount = document.getElementById('discountAmount');

    let totalQuantity = 0;
    let subtotal = 0;

    saleItems.forEach(function (item) {
        totalQuantity += item.quantity;
        subtotal += item.price * item.quantity;
    });

    const discount = Number(discountAmount.value) || 0;
    const total = Math.max(0, subtotal - discount);

    sumDistinct.textContent = saleItems.length;
    sumQty.textContent = totalQuantity;
    sumSubtotal.textContent = formatMoney(subtotal);
    sumTotal.textContent = formatMoney(total);
}

// Formatar moeda
function formatMoney(value) {
    return 'R$ ' + value.toFixed(2).replace('.', ',');
}

// Atualizar produtos
function updateSaleItemsInput() {
    const saleItemsInput = document.getElementById('saleItems');
    saleItemsInput.value = JSON.stringify(
        saleItems.map(function (item) {
            return {
                id: item.id,
                quantity: item.quantity
            };
        })
    );
}

// Desconto
const discountAmount = document.getElementById('discountAmount');

discountAmount.addEventListener('input', function () {
    updateSummary();
});

// Atualizar produtos antes de enviar a venda
const saleForm = document.querySelector('form');

saleForm.addEventListener('submit', function () {
    updateSaleItemsInput();
});

// Carrega itens ao editar
renderItems();
updateSummary();
updateSaleItemsInput();