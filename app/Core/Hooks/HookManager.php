<?php

namespace App\Core\Hooks;

use Illuminate\Support\Facades\Log;
use Closure;

class HookManager
{
    protected array $actions = [];
    protected array $filters = [];

    /**
     * Register a callback for an action hook.
     */
    public function addAction(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->actions[$hook][$priority][] = [
            'callback' => $callback,
            'accepted_args' => $acceptedArgs
        ];
    }

    /**
     * Register a callback for a filter hook.
     */
    public function addFilter(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->filters[$hook][$priority][] = [
            'callback' => $callback,
            'accepted_args' => $acceptedArgs
        ];
    }

    /**
     * Execute actions registered for a hook.
     */
    public function doAction(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        // Sort by priority (ascending)
        ksort($this->actions[$hook]);

        foreach ($this->actions[$hook] as $priority => $listeners) {
            foreach ($listeners as $listener) {
                try {
                    $callback = $listener['callback'];
                    $acceptedArgs = $listener['accepted_args'];

                    // Slice arguments to only pass what the callback expects
                    $passedArgs = array_slice($args, 0, $acceptedArgs);

                    $this->executeCallback($callback, $passedArgs);
                } catch (\Throwable $e) {
                    Log::error("Error executing action hook [{$hook}] callback: " . $e->getMessage(), [
                        'exception' => $e,
                        'callback' => $listener['callback']
                    ]);
                }
            }
        }
    }

    /**
     * Apply filters registered for a hook.
     */
    public function applyFilters(string $hook, mixed $value, ...$args): mixed
    {
        if (!isset($this->filters[$hook])) {
            return $value;
        }

        // Sort by priority (ascending)
        ksort($this->filters[$hook]);

        foreach ($this->filters[$hook] as $priority => $listeners) {
            foreach ($listeners as $listener) {
                try {
                    $callback = $listener['callback'];
                    $acceptedArgs = $listener['accepted_args'];

                    // The first argument passed to the filter callback is the current value
                    $passedArgs = array_merge([$value], array_slice($args, 0, $acceptedArgs - 1));

                    $value = $this->executeCallback($callback, $passedArgs);
                } catch (\Throwable $e) {
                    Log::error("Error executing filter hook [{$hook}] callback: " . $e->getMessage(), [
                        'exception' => $e,
                        'callback' => $listener['callback']
                    ]);
                }
            }
        }

        return $value;
    }

    /**
     * Get all registered actions.
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Get all registered filters.
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Execute callback safely.
     */
    protected function executeCallback(mixed $callback, array $args): mixed
    {
        if ($callback instanceof Closure) {
            return $callback(...$args);
        }

        if (is_array($callback) && count($callback) === 2) {
            [$classOrObj, $method] = $callback;
            if (is_string($classOrObj)) {
                $instance = app($classOrObj);
                return call_user_func_array([$instance, $method], $args);
            }
            return call_user_func_array([$classOrObj, $method], $args);
        }

        if (is_string($callback) && str_contains($callback, '@')) {
            [$class, $method] = explode('@', $callback);
            $instance = app($class);
            return call_user_func_array([$instance, $method], $args);
        }

        return call_user_func_array($callback, $args);
    }
}
