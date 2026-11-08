<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Intercepta a requisição HTTP para garantir que usuários inativados
     * tenham sua sessão revogada imediatamente em qualquer tela do ERP.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se existe um usuário autenticado na sessão, mas ele foi marcado como inativo:
        if ($user !== null && ! $user->is_active) {
            // 1. Encerra a autenticação no guard 'web'
            Auth::guard('web')->logout();

            // 2. Invalida os dados da sessão atual no servidor (tabela sessions no Postgres)
            $request->session()->invalidate();

            // 3. Gera um novo token CSRF para evitar ataques de reutilização de sessão
            $request->session()->regenerateToken();

            // 4. Redireciona para o login enviando o erro para o componente Vue exibir na tela
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Sua conta está inativa. Entre em contato com o administrador.',
                ]);
        }

        // Caso esteja ativo (ou seja um visitante ainda não logado), segue o fluxo normal
        return $next($request);
    }
}
