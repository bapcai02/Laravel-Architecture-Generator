<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Commands\CreateUserCommand;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserService $userService, UserRepositoryInterface $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Using Service Layer
        $users = $this->userService->getAll();
        
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'architecture' => 'Service Layer Pattern'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Using Service Layer
        $user = $this->userService->create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
            'architecture' => 'Service Layer Pattern'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Using Repository Pattern
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user,
            'architecture' => 'Repository Pattern'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id
        ]);

        // Using Repository Pattern
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $this->userRepository->update($user, $validated);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user->fresh(),
            'architecture' => 'Repository Pattern'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Using Service Layer
        $deleted = $this->userService->delete($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'User deleted successfully',
            'architecture' => 'Service Layer Pattern'
        ]);
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        // Using Service Layer
        $users = $this->userService->search($query);

        return response()->json([
            'message' => 'Search completed',
            'data' => $users,
            'query' => $query,
            'architecture' => 'Service Layer Pattern'
        ]);
    }

    /**
     * Test CQRS Command
     */
    public function testCqrs(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        // Create CQRS Command
        $command = new CreateUserCommand(
            $validated['name'],
            $validated['description'],
            $validated
        );

        return response()->json([
            'message' => 'CQRS Command created successfully',
            'command_data' => $command->toArray(),
            'architecture' => 'CQRS Pattern'
        ]);
    }
}
