<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Define quais caminhos da sua API devem permitir CORS. Adicione outros caminhos conforme necessário.
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'register', 'auth/login', 'logout'], 

    // Métodos HTTP permitidos. '*' permite todos os métodos.
    'allowed_methods' => ['*'],

    // Origens permitidas para acessar a API. '*' permite todas as origens.
    'allowed_origins' => ['*'],

    // Padrões de origens permitidos usando expressões regulares.
    'allowed_origins_patterns' => [],

    // Cabeçalhos permitidos nas requisições.
    'allowed_headers' => ['*'],

    // Cabeçalhos que podem ser expostos às solicitações.
    'exposed_headers' => [],

    // Tempo máximo em segundos que os resultados de uma solicitação prévia (preflight request) podem ser armazenados em cache.
    'max_age' => 0,

    // Suporte a credenciais (cookies, authorization headers, etc). 
    'supports_credentials' => false,

];