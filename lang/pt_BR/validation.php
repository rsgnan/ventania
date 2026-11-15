<?php

return [
    'array' => 'O campo :attribute deve ser uma lista válida.',
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'current_password' => 'A senha atual informada está incorreta.',
    'distinct' => 'O campo :attribute possui um item duplicado na lista.',
    'email' => 'Informe um endereço de :attribute válido.',
    'enum' => 'A opção selecionada para :attribute é inválida.',
    'exists' => 'O :attribute selecionado é inválido ou está inativo.',
    'image' => 'O arquivo enviado em :attribute deve ser uma imagem.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'lowercase' => 'O campo :attribute deve estar em letras minúsculas.',
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O arquivo em :attribute não pode ser maior que :max kilobytes (2 MB).',
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
    ],
    'mimes' => 'A imagem deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O valor de :attribute deve ser no mínimo :min.',
        'file' => 'O arquivo em :attribute deve ter no mínimo :min kilobytes.',
        'string' => 'A :attribute deve conter no mínimo :min caracteres.',
        'array' => 'Selecione pelo menos :min item em :attribute.',
    ],
    'numeric' => 'O campo :attribute deve ser um valor numérico.',
    'password' => [
        'letters' => 'A :attribute deve conter pelo menos uma letra.',
        'mixed' => 'A :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'A :attribute deve conter pelo menos um número.',
        'symbols' => 'A :attribute deve conter pelo menos um símbolo.',
        'uncompromised' => 'A :attribute informada apareceu em um vazamento de dados. Escolha outra.',
    ],
    'required' => 'O preenchimento de :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto válido.',
    'unique' => 'Este :attribute já está cadastrado em outra conta ou registro.',

    /*
    |--------------------------------------------------------------------------
    | Mensagens Personalizadas por Campo e Regra (custom)
    |--------------------------------------------------------------------------
    */
    'custom' => [
        'email' => [
            'unique' => 'Este endereço de e-mail já está vinculado a outro usuário do sistema.',
        ],
        'name' => [
            'unique' => 'Já existe um produto ou kit cadastrado com este nome no catálogo.',
        ],
        'sku' => [
            'unique' => 'Este código SKU já está sendo utilizado por outro item do catálogo.',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Tradução dos Nomes dos Campos (:attribute) em Todo o ERP
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'name' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
        'current_password' => 'senha atual',
        'role' => 'cargo',
        'is_active' => 'status',
        'category_id' => 'categoria',
        'sku' => 'código SKU',
        'type' => 'tipo',
        'price' => 'preço de venda',
        'min_stock' => 'estoque mínimo',
        'description' => 'descrição',
        'image' => 'imagem',
        'components' => 'composição do kit',
        'items' => 'itens',
        'quantity' => 'quantidade',
        'notes' => 'observação',
        'customer_name' => 'nome do cliente',
        'payment_method' => 'forma de pagamento',
        'discount' => 'desconto',
        'status' => 'status',
    ],
];
