<?php

namespace App\Http\Controllers;

use App\Models\AccountRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountRequestController extends Controller
{
    public function create()
    {
        return view('guest.account-request');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:account_requests',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'justification' => 'required|string|min:50',
        ]);

        AccountRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'justification' => $validated['justification'],
            'status' => 'en_attente',
        ]);

        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Administrateur');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Nouvelle demande de compte',
                'message' => "Une nouvelle demande de compte a été soumise par {$validated['name']} ({$validated['email']})",
                'type' => 'info',
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Votre demande de compte a été soumise avec succès! Vous recevrez une réponse par email.');
    }

    public function index()
    {
        $requests = AccountRequest::with('processor')
            ->latest()
            ->paginate(15);

        return view('admin.account-requests.index', compact('requests'));
    }

    public function approve(Request $request, AccountRequest $accountRequest)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $userRole = Role::where('name', 'Utilisateur interne')->first();

        $user = User::create([
            'name' => $accountRequest->name,
            'email' => $accountRequest->email,
            'password' => Hash::make($validated['password']),
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        $accountRequest->update([
            'status' => 'approuvee',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.account-requests.index')
            ->with('success', 'Demande approuvée et compte créé avec succès!');
    }

    public function reject(Request $request, AccountRequest $accountRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $accountRequest->update([
            'status' => 'refusee',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.account-requests.index')
            ->with('success', 'Demande refusée.');
    }
}
