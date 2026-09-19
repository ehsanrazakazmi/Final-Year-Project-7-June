<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // Was fake()->unique()->numberBetween(1,3), which throws an
            // OverflowException once a fourth user is created. Use the role
            // states below to make an admin or technician instead.
            'role' => User::ROLE_RESIDENT,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->withRole('admin');
    }

    public function resident(): static
    {
        return $this->withRole('resident');
    }

    public function technician(): static
    {
        return $this->withRole('technician');
    }

    /**
     * Sets the legacy `role` integer AND the Spatie role together - the same
     * pairing User::assignRoleByName() maintains in the application.
     */
    protected function withRole(string $name): static
    {
        return $this->state(fn () => ['role' => User::roleValueFor($name)])
            ->afterCreating(function (User $user) use ($name) {
                Role::findOrCreate($name, 'web');
                $user->assignRoleByName($name);
            });
    }
}
