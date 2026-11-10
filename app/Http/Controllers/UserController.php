<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Exibe a listagem paginada de usuários com filtros e KPIs superiores.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', User::class);

        $users = User::query()
            ->with('latestLogin')
            ->when($request->string('search')->trim()->value(), function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                });
            })
            ->when($request->string('role')->value(), fn(Builder $query, string $role) => $query->where('role', $role))
            ->when($request->string('status')->value(), function (Builder $query, string $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
            'roles' => UserRole::options(),
            'stats' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'admins' => User::where('role', UserRole::Admin)->count(),
            ],
        ]);
    }

    /**
     * Cadastra um novo usuário no ERP (acionado pelo painel lateral <Sheet>).
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['required', 'boolean'],
        ]);

        User::create($validated);

        return back();
    }

    /**
     * Atualiza um usuário existente garantindo que o Admin logado não bloqueie a si mesmo.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::defaults()],
            'role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['required', 'boolean'],
        ]);

        // Trava de autoproteção: impede que o administrador logado desative a si mesmo ou remova seu próprio cargo de Admin
        if ($request->user()->is($user) && (! $validated['is_active'] || $validated['role'] !== UserRole::Admin->value)) {
            throw ValidationException::withMessages([
                'role' => __('users.self_lockout'),
            ]);
        }

        // Na edição, se o campo de senha vier em branco, mantém a senha atual intacta
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return back();
    }
}
