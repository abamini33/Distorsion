<?php

declare(strict_types=1);

// Définition des routes
const AVAILABLE_ROUTES = [
    'chat' => 'MessageController',
    'add_category' => 'CategoryController',
    'add_room' => 'RoomController',
    'about' => 'AboutController',
    'toggle_pin' => 'MessageController'
];

const DEFAULT_ROUTE = 'chat';
