<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
     * Cadastra um novo usuário no ERP (autorizado e validado via StoreUserRequest).
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return back();
    }


    /**
     * Atualiza um usuário existente (autorizado e protegido contra auto-bloqueio via UpdateUserRequest).
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update(array_filter($request->validated(), fn($value) => $value !== null));

        return back();
    }
}
