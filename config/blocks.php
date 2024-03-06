<?php

return [
    'start' => [
        'name' => 'start',
        'title' => 'Start',
        'description' => 'Start block',
        'type' => 'start',
    ],
    'end' => [
        'name' => 'end',
        'title' => 'End',
        'description' => 'End block',
        'type' => 'end',
    ],
    'if' => [
        'name' => 'if',
        'title' => 'If',
        'description' => 'Check if condition is true',
        'type' => 'logic',
        'ports' => [
            [
                'name' => 'In',
                'type' => 'flow',
                'direction' => 0,
            ],
            [
                'name' => 'True',
                'type' => 'flow',
                'direction' => 1,
            ],
            [
                'name' => 'False',
                'type' => 'flow',
                'direction' => 1,
            ],
            [
                'name' => 'Condition',
                'type' => 'boolean',
                'direction' => 0,
            ],
        ]
    ],
    'sum' => [
        'name' => 'sum',
        'title' => 'Sum',
        'description' => 'Sum A and B',
        'type' => 'math',
        'ports' => [
            [
                'name' => 'A',
                'type' => 'number',
                'direction' => 0,
            ],
            [
                'name' => 'B',
                'type' => 'number',
                'direction' => 0,
            ],
            [
                'name' => 'Result',
                'type' => 'number',
                'direction' => 1,
            ],
        ]
    ],
    'subtract' => [
        'name' => 'subtract',
        'title' => 'Subtract',
        'description' => 'Subtract B from A',
        'type' => 'math',
        'ports' => [
            [
                'name' => 'A',
                'type' => 'number',
                'direction' => 0,
            ],
            [
                'name' => 'B',
                'type' => 'number',
                'direction' => 0,
            ],
            [
                'name' => 'Result',
                'type' => 'number',
                'direction' => 1,
            ],
        ]
    ],
    'log' => [
        'name' => 'log',
        'title' => 'Log',
        'description' => 'Log message',
        'type' => 'method',
        'ports' => [
            [
                'name' => 'In',
                'type' => 'flow',
                'direction' => 0,
            ],
            [
                'name' => 'Out',
                'type' => 'flow',
                'direction' => 1,
            ],
            [
                'name' => 'Message',
                'type' => 'any',
                'direction' => 0,
            ],
        ]
    ],
    'rand' => [
        'name' => 'rand',
        'title' => 'Random',
        'description' => 'Generate random number',
        'type' => 'math',
        'ports' => [
            [
                'name' => 'Result',
                'type' => 'number',
                'direction' => 1,
            ],
        ]
    ],
    'dbwrite' => [
        'name' => 'dbwrite',
        'title' => 'DB: write object',
        'description' => 'Write object to Database',
        'type' => 'database',
        'ports' => [
            [
                'name' => 'In',
                'type' => 'flow',
                'direction' => 0,
            ],
            [
                'name' => 'Out',
                'type' => 'flow',
                'direction' => 1,
            ],
            [
                'name' => 'Table',
                'type' => 'string',
                'direction' => 0,
            ],
            [
                'name' => 'Object',
                'type' => 'any',
                'direction' => 0,
            ],
        ]
    ]
];
