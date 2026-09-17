const saleItems = [];

const searchInput = document.getElementById('searchInput');
const productSearchResults = document.getElementById('productSearchResults');
const itemsBody = document.getElementById('itemsBody');
const saleItemsInput = document.getElementById('saleItems');
const discountAmount = document.getElementById('discountAmount');
const saleForm = document.querySelector('form');

let searchTimeout;

searchInput.addEventListener('input', function () {
    clearTimeout(searchTimeout);

    const search = this.value.trim();

    productSearchResults.innerHTML = '';

    if (search === '') {
        productSearchResults.style.display = 'none';
        return;
    }

    searchTimeout = setTimeout(function () {
        searchProducts(search);
    }, 300);
});

async function searchProducts(search) {
    const params = new URLSearchParams({
        route: 'sales/searchProducts',
        search: search
    });

    try {
        const response = await fetch('?' + params.toString());

        if (!response.ok) {
            throw new Error();
        }

        const products = await response.json();

        renderSearchResults(products);

    } catch {
        productSearchResults.innerHTML = '';

        const empty = document.createElement('div');

        empty.className = 'search-empty';
        empty.textContent = 'Não foi possível buscar os produtos.';

        productSearchResults.appendChild(empty);
        productSearchResults.style.display = 'block';
    }
}

function renderSearchResults(products) {
    productSearchResults.innerHTML = '';

    if (products.length === 0) {
        const empty = document.createElement('div');

        empty.className = 'search-empty';
        empty.textContent = 'Nenhum produto encontrado.';

        productSearchResults.appendChild(empty);
        productSearchResults.style.display = 'block';

        return;
    }

    products.forEach(function (product) {
        const result = document.createElement('div');
        const content = document.createElement('div');
        const name = document.createElement('strong');
        const stock = document.createElement('small');
        const price = document.createElement('span');

        result.className = 'search-item';

        name.textContent = product.name;
        stock.textContent = 'Estoque: ' + product.stock;
        price.textContent = formatMoney(Number(product.price));

        content.appendChild(name);
        content.appendChild(stock);

        result.appendChild(content);
        result.appendChild(price);

        result.addEventListener('click', function () {
            addProduct(product);
        });

        productSearchResults.appendChild(result);
    });

    productSearchResults.style.display = 'block';
}

function addProduct(product) {
    const productId = Number(product.id);

    const existingItem = saleItems.find(function (item) {
        return item.id === productId;
    });

    if (existingItem) {
        if (existingItem.quantity < existingItem.stock) {
            existingItem.quantity++;
        }
    } else {
        const stock = Number(product.stock);

        if (stock <= 0) {
            return;
        }

        saleItems.push({
            id: productId,
            name: product.name,
            price: Number(product.price),
            stock: stock,
            quantity: 1
        });
    }

    searchInput.value = '';
    productSearchResults.innerHTML = '';
    productSearchResults.style.display = 'none';

    updateSale();
}

function renderItems() {
    itemsBody.innerHTML = '';

    if (saleItems.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        const empty = document.createElement('div');
        const title = document.createElement('strong');
        const description = document.createElement('span');

        cell.colSpan = 5;
        empty.className = 'sale-empty';

        title.textContent = 'Nenhum produto adicionado.';
        description.textContent = 'Busque um produto para começar.';

        empty.appendChild(title);
        empty.appendChild(description);
        cell.appendChild(empty);
        row.appendChild(cell);
        itemsBody.appendChild(row);

        return;
    }

    saleItems.forEach(function (item) {
        const row = document.createElement('tr');

        const productCell = document.createElement('td');
        const quantityCell = document.createElement('td');
        const priceCell = document.createElement('td');
        const subtotalCell = document.createElement('td');
        const actionCell = document.createElement('td');

        const quantityInput = document.createElement('input');
        const removeButton = document.createElement('button');

        productCell.textContent = item.name;

        quantityInput.type = 'number';
        quantityInput.min = 1;
        quantityInput.max = item.stock;
        quantityInput.value = item.quantity;
        quantityInput.className = 'item-quantity';

        priceCell.textContent = formatMoney(item.price);
        subtotalCell.textContent = formatMoney(
            item.price * item.quantity
        );

        removeButton.type = 'button';
        removeButton.className = 'btn-remove';
        removeButton.textContent = 'Remover';

        quantityCell.appendChild(quantityInput);
        actionCell.appendChild(removeButton);

        row.appendChild(productCell);
        row.appendChild(quantityCell);
        row.appendChild(priceCell);
        row.appendChild(subtotalCell);
        row.appendChild(actionCell);

        itemsBody.appendChild(row);

        quantityInput.addEventListener('change', function () {
            let quantity = parseInt(this.value, 10);

            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }

            if (quantity > item.stock) {
                quantity = item.stock;
            }

            item.quantity = quantity;

            updateSale();
        });

        removeButton.addEventListener('click', function () {
            const index = saleItems.findIndex(function (saleItem) {
                return saleItem.id === item.id;
            });

            if (index !== -1) {
                saleItems.splice(index, 1);
            }

            updateSale();
        });
    });
}

function updateSummary() {
    let totalQuantity = 0;
    let subtotal = 0;

    saleItems.forEach(function (item) {
        totalQuantity += item.quantity;
        subtotal += item.price * item.quantity;
    });

    const discount = Number(discountAmount.value) || 0;
    const total = Math.max(0, subtotal - discount);

    document.getElementById('sumDistinct').textContent = saleItems.length;
    document.getElementById('sumQty').textContent = totalQuantity;
    document.getElementById('sumSubtotal').textContent = formatMoney(subtotal);
    document.getElementById('sumTotal').textContent = formatMoney(total);
}

function updateSaleItemsInput() {
    saleItemsInput.value = JSON.stringify(
        saleItems.map(function (item) {
            return {
                id: item.id,
                quantity: item.quantity
            };
        })
    );
}

function updateSale() {
    renderItems();
    updateSummary();
    updateSaleItemsInput();
}

function formatMoney(value) {
    return 'R$ ' + value.toFixed(2).replace('.', ',');
}

async function loadExistingItems() {
    if (
        typeof existingSaleItems === 'undefined'
        || existingSaleItems.length === 0
    ) {
        updateSale();
        return;
    }

    const params = new URLSearchParams({
        route: 'sales/searchProducts'
    });

    existingSaleItems.forEach(function (item) {
        const productId = item.product_id ?? item.id;

        params.append('product_ids[]', productId);
    });

    try {
        const response = await fetch('?' + params.toString());

        if (!response.ok) {
            throw new Error();
        }

        const products = await response.json();

        existingSaleItems.forEach(function (existingItem) {
            const productId = Number(
                existingItem.product_id ?? existingItem.id
            );

            const product = products.find(function (product) {
                return Number(product.id) === productId;
            });

            if (!product) {
                return;
            }

            const quantity = Number(existingItem.quantity);

            const originalItem = originalSaleItems.find(function (item) {
                return Number(item.product_id) === productId;
            });

            const originalQuantity = originalItem
                ? Number(originalItem.quantity)
                : 0;

            saleItems.push({
                id: productId,
                name: existingItem.product_name ?? product.name,
                price: Number(existingItem.unit_price ?? product.price),
                stock: Number(product.stock) + originalQuantity,
                quantity: quantity
            });
        });
    } catch {
        saleItems.length = 0;
    }

    updateSale();
}

discountAmount.addEventListener('input', function () {
    updateSummary();
});

saleForm.addEventListener('submit', function () {
    updateSaleItemsInput();
});

loadExistingItems();
