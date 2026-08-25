<?php

namespace App\Core;

class Container
{
    private array $instances = [];
    private array $recipes = [];

    public function bind(string $id, \Closure $recipe): void
    {
        $this->recipes[$id] = $recipe;
    }

    public function get(string $id)
    {
        if (!isset($this->instances[$id])) {
            if (!isset($this->recipes[$id])) {
                throw new \RuntimeException(
                    "Could not build: {$id}."
                );
            }

            $this->instances[$id] = $this->recipes[$id]();
        }

        return $this->instances[$id];
    }
}
