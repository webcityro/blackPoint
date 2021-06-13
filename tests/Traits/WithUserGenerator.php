<?php

namespace Tests\Traits;

trait WithUserGenerator
{
    protected function generateUser(array $fields = []): array
    {
        return array_merge([
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secretPassword',
            'role_id' => 1,
            'active' => 1
        ], $fields);
    }
}
