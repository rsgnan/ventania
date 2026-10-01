<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'exists' => 'O valor selecionado para :attribute é inválido',

    'max' => [
        'string' => 'O campo :attribute deve ter no máximo :max caracteres.',
    ],

    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
    ],

    'attributes' => [
        'name' => 'nome',
        'category_id' => 'categoria',
        'price' => 'preço',
        'minimum_stock' => 'estoque minímo',
        'description' => 'descrição',
    ],
];
