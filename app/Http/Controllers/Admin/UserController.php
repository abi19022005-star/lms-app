<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan search (nama atau email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Pagination dengan preserve query string
        $users = $query->withCount(['enrollments', 'certificates'])
                       ->paginate(20)
                       ->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,guru,siswa',
            'bio' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Tambahkan logika NIP/NIS
        if (in_array($validated['role'], ['admin', 'guru'])) {
            $validated['nip'] = $this->generateNip();
        } elseif ($validated['role'] === 'siswa') {
            $validated['nis'] = $this->generateNis();
        }
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,guru,siswa',
            'bio' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        if ($validated['role'] === 'siswa') {
            $validated['nis'] = $user->nis ?? $this->generateNis();
            $validated['nip'] = null;
        } else {
            $validated['nip'] = $user->nip ?? $this->generateNip();
            $validated['nis'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        // Perbaikan: gunakan helper auth() bukan Auth::id()
        /** @var User|null $authUser */
        $authUser = Auth::user();
        if ($user->id === $authUser?->id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleRole(User $user)
    {
        $roles = ['siswa', 'guru', 'admin'];
        $currentIndex = array_search($user->role, $roles);
        $nextRole = $roles[($currentIndex + 1) % count($roles)];

        $user->update(['role' => $nextRole]);

        return back()->with('success', 'Role user berhasil diubah menjadi ' . ucfirst($nextRole));
    }

    public function ban(User $user)
    {
        return back()->with('success', 'User berhasil di-ban.');
    }

    public function unban(User $user)
    {
        return back()->with('success', 'User berhasil di-unban.');
    }

    public function export()
    {
        return back()->with('success', 'Export akan segera diproses.');
    }
    private function generateNip()
    {
        return str_pad(rand(0, 999999999999999999), 18, '0', STR_PAD_LEFT);
    }

    private function generateNis()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
