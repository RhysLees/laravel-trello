<?php

return [
    'auth' => [
        'key' => env('TRELLO_API_KEY', ''),
        'token' => env('TRELLO_API_TOKEN', ''),
    ],

    // Define Your trello board id here
    'board' => [
        'id' => '',
    ],


    // Define Your trello lists here
    // 'list_name' => 'listid'
    'lists' => [

    ],

    /* Define Predefined trello checklists here
        'checklist_key' => [
            'name' => 'checklist_name',
            'items' => [
                'item name',
            ]
        ]
    */
    'checklists' => [

    ],
];
