<?php

return [
    // Chaves padrão do Laravel para falha de credenciais e bloqueio por excesso de tentativas (Rate Limiter)
    'failed' => 'As credenciais informadas não conferem com nossos registros.',
    'password' => 'A senha informada está incorreta.',
    'throttle' => 'Muitas tentativas de login. Tente novamente em :seconds segundos.',

    // Chave customizada do ERP Ventania para contas com is_active = false
    'inactive' => 'Sua conta está inativa. Entre em contato com o administrador.',
];
