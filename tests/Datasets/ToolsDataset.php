<?php

dataset('valid tool data', [
    [[
        'title' => 'Test Tool',
        'link' => 'https://example.com',
        'description' => 'This is a test tool.',
        'tags' => ['node', 'calendar'],
    ]],
]);

dataset('invalid tool data', [
    'título ausente' => [
        ['link' => 'https://example.com', 'description' => 'Desc', 'tags' => ['php']],
        'title'
    ],
    'título não string' => [
        ['title' => 12345, 'link' => 'https://example.com', 'description' => 'Desc', 'tags' => ['php']],
        'title'
    ],
    'título muito longo' => [
        ['title' => str_repeat('a', 256), 'link' => 'https://example.com', 'description' => 'Desc', 'tags' => ['php']],
        'title'
    ],

    'link ausente' => [
        ['title' => 'Test Tool', 'link' => '', 'description' => 'Desc', 'tags' => ['php']],
        'link'
    ],
    'link com URL inválida' => [
        ['title' => 'Test Tool', 'link' => 'link-invalido-sem-http', 'description' => 'Desc', 'tags' => ['php']],
        'link'
    ],

    'descrição não string' => [
        ['title' => 'Test Tool', 'link' => 'https://example.com', 'description' => ['array-invalido'], 'tags' => ['php']],
        'description'
    ],

    'tags como string em vez de array' => [
        ['title' => 'Test Tool', 'link' => 'https://example.com', 'description' => 'Desc', 'tags' => 'laravel,php'],
        'tags'
    ],
    'tags contendo item inválido (ex: número)' => [
        ['title' => 'Test Tool', 'link' => 'https://example.com', 'description' => 'Desc', 'tags' => ['laravel', 123]],
        'tags.1'
    ],
    'tags contendo tag que não existe no banco' => [
        ['title' => 'Test Tool', 'link' => 'https://example.com', 'description' => 'Desc', 'tags' => ['tag-que-nao-existe']],
        'tags.0'
    ],
]);
