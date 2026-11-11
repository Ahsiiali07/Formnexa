<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Parse either an array or comma-separated string of guest UUIDs.
     *
     * @return array<int, string>
     */
    protected function parseUuidList(mixed $raw): array
    {
        if (is_array($raw)) {
            return array_values(array_filter(array_map('trim', array_map('strval', $raw))));
        }

        if (is_string($raw) && trim($raw) !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $raw))));
        }

        return [];
    }
}
