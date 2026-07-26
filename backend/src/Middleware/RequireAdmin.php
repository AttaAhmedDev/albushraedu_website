<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';

class RequireAdmin
{
    public static function handle(): void
    {
        if (!Auth::isAdmin()) {
            Response::error('Unauthorized', 403);
        }
    }
}
