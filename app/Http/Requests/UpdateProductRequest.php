<?php

namespace App\Http\Requests;

class UpdateProductRequest extends StoreProductRequest
{
    // Herda authorize() e rules() do StoreProductRequest, já ignorando o SKU do produto atual via $this->route('product')
}
