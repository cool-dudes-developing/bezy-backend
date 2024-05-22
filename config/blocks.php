<?php

return [
    'logic' => [
        'flow' => [
            'start' => [
                'name' => 'start',
                'title' => 'Start',
                'description' => 'Start block of any flow. Always the first block in the method. You can add input variables for your method here.',
                'type' => 'start',
            ],
            'end' => [
                'name' => 'end',
                'title' => 'End',
                'description' => 'Finish block of any flow. Always the last block in the method.',
                'type' => 'end',
            ],
            'raise' => [
                'name' => 'raise',
                'title' => 'Raise Error',
                'description' => 'Raise error and stop execution',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'In',
                        'type' => 'flow',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Error',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                ]
            ],
//            'return' => [
//                'name' => 'return',
//                'title' => 'Return',
//                'description' => 'Return value from method',
//                'type' => 'logic',
//            ],
            'ifelse' => [
                'name' => 'ifelse',
                'title' => 'If-Else',
                'description' => 'Check if condition is true, otherwise execute else block',
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
                        'type' => 'any',
                        'direction' => 0,
                    ],
                ]
            ],
            'loop' => [
                'name' => 'loop',
                'title' => 'Loop',
                'description' => 'Loop over array',
                'type' => 'logic',
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
                        'name' => 'Loop',
                        'type' => 'flow',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Items',
                        'type' => 'array',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Item',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Index',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'trycatch' => [
                'name' => 'trycatch',
                'title' => 'Try-Catch',
                'description' => 'Try to execute block, otherwise catch error',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'In',
                        'type' => 'flow',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Try',
                        'type' => 'flow',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Catch',
                        'type' => 'flow',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Error',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Finally',
                        'type' => 'flow',
                        'direction' => 1,
                    ]
                ]
            ],
            'break' => [
                'name' => 'break',
                'title' => 'Break Loop',
                'description' => 'Break loop execution and continue with Out loop flow',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'In',
                        'type' => 'flow',
                        'direction' => 0,
                    ],
                ]
            ],
        ],
        'comparison' => [
            'equal' => [
                'name' => 'equal',
                'title' => 'Equal',
                'description' => 'Check if A is equal to B',
                'type' => 'comparison',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'greater' => [
                'name' => 'greater',
                'title' => 'Greater',
                'description' => 'Check if A is greater than B',
                'type' => 'comparison',
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
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'greater_equal' => [
                'name' => 'greater_equal',
                'title' => 'Greater or Equal',
                'description' => 'Check if A is greater or equal to B',
                'type' => 'comparison',
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
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'less' => [
                'name' => 'less',
                'title' => 'Less',
                'description' => 'Check if A is less than B',
                'type' => 'comparison',
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
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'less_equal' => [
                'name' => 'less_equal',
                'title' => 'Less or Equal',
                'description' => 'Check if A is less or equal to B',
                'type' => 'comparison',
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
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'not_equal' => [
                'name' => 'not_equal',
                'title' => 'Not Equal',
                'description' => 'Check if A is not equal to B',
                'type' => 'comparison',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'isnull' => [
                'name' => 'isnull',
                'title' => 'Is Null',
                'description' => 'Check if A is null',
                'type' => 'comparison',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'utils' => [
            'set' => [
                'name' => 'set',
                'title' => 'Set Variable',
                'description' => 'Set variable to value for later use',
                'type' => 'utils',
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
                        'name' => 'Variable',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Value',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                ]
            ],
            'get' => [
                'name' => 'get',
                'title' => 'Get Variable',
                'description' => 'Get variable value',
                'type' => 'utils',
                'ports' => [
                    [
                        'name' => 'Variable',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Value',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                ]
            ],
            'log' => [
                'name' => 'log',
                'title' => 'Log',
                'description' => 'Log message to console',
                'type' => 'utils',
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
        ],
        'constants' => [
            'true' => [
                'name' => 'true',
                'title' => 'True',
                'description' => 'Return true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'True',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'false' => [
                'name' => 'false',
                'title' => 'False',
                'description' => 'Return false',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'False',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'null' => [
                'name' => 'null',
                'title' => 'Null',
                'description' => 'Return null',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'Null',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                ]
            ],
            'zero' => [
                'name' => 'zero',
                'title' => 'Zero',
                'description' => 'Return zero',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'Zero',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'one' => [
                'name' => 'one',
                'title' => 'One',
                'description' => 'Return one',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'One',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'number' => [
                'name' => 'number',
                'title' => 'Number',
                'description' => 'Return number',
                'type' => 'variable',
                'ports' => [
                    [
                        'name' => 'Number',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'string' => [
                'name' => 'string',
                'title' => 'String',
                'description' => 'Return string',
                'type' => 'variable',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
        ]
    ],
    'functions' => [
        'math' => [
            'sum' => [
                'name' => 'sum',
                'title' => 'Sum',
                'description' => 'Sum A and B and return the result',
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
                'description' => 'Subtract B from A and return the result',
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
            'rand' => [
                'name' => 'rand',
                'title' => 'Random',
                'description' => 'Generate random number between A and B',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Min',
                        'type' => 'number',
                        'direction' => 0,
                        'default' => 0
                    ],
                    [
                        'name' => 'Max',
                        'type' => 'number',
                        'direction' => 0,
                        'default' => 100
                    ],
                ]
            ],
            'multiply' => [
                'name' => 'multiply',
                'title' => 'Multiply',
                'description' => 'Multiply A and B and return the result',
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
            'divide' => [
                'name' => 'divide',
                'title' => 'Divide',
                'description' => 'Divide A by B and return the result',
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
            'round' => [
                'name' => 'round',
                'title' => 'Round',
                'description' => 'Round A to the nearest integer',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'A',
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
            'round_up' => [
                'name' => 'round_up',
                'title' => 'Round Up',
                'description' => 'Round A up to the nearest integer',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'A',
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
            'round_down' => [
                'name' => 'round_down',
                'title' => 'Round Down',
                'description' => 'Round A down to the nearest integer',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'A',
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
            'mod' => [
                'name' => 'mod',
                'title' => 'Modulo',
                'type' => 'math',
                'description' => 'The modulo operation finds the remainder after division of one number by another',
                'ports' => [
                    [
                        'name' => 'Dividend',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Divisor',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Remainder',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ],
            ],
            'abs' => [
                'name' => 'abs',
                'title' => 'Absolute',
                'description' => 'Return the absolute value of X',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'X',
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
            'sqrt' => [
                'name' => 'sqrt',
                'title' => 'Square Root',
                'description' => 'Return the square root of X',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'X',
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
            'pow' => [
                'name' => 'pow',
                'title' => 'Power',
                'description' => 'Return X raised to the power',
                'type' => 'math',
                'ports' => [
                    [
                        'name' => 'X',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Power',
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
            'sin' => [
                'name' => 'sin',
                'title' => 'Sine',
                'description' => 'Return the sine of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'cos' => [
                'name' => 'cos',
                'title' => 'Cosine',
                'description' => 'Return the cosine of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'tan' => [
                'name' => 'tan',
                'title' => 'Tangent',
                'description' => 'Return the tangent of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'cot' => [
                'name' => 'cot',
                'title' => 'Cotangent',
                'description' => 'Return the cotangent of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'asin' => [
                'name' => 'asin',
                'title' => 'Arcsine',
                'description' => 'Return the arcsine of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'acos' => [
                'name' => 'acos',
                'title' => 'Arccosine',
                'description' => 'Return the arccosine of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'atan' => [
                'name' => 'atan',
                'title' => 'Arctangent',
                'description' => 'Return the arctangent of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'acot' => [
                'name' => 'acot',
                'title' => 'Arccotangent',
                'description' => 'Return the arccotangent of X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'X',
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
            'atan2' => [
                'name' => 'atan2',
                'title' => 'Arctangent 2',
                'description' => 'Return the arctangent of Y/X',
                'type' => 'trigonometry',
                'ports' => [
                    [
                        'name' => 'Y',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'X',
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
        ],
        'text' => [
            'rand_str' => [
                'name' => 'rand_str',
                'title' => 'Random String',
                'description' => 'Generate random string of length N',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                    [
                        'name' => 'Length',
                        'type' => 'number',
                        'direction' => 0,
                        'default' => 10
                    ],
                ]
            ],
            'slugify' => [
                'name' => 'slugify',
                'title' => 'Slugify',
                'description' => 'Convert string to slug',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'concat' => [
                'name' => 'concat',
                'title' => 'Concatenate',
                'description' => 'Concatenate strings and return the result',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'Strings',
                        'type' => 'array<string>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'split' => [
                'name' => 'split',
                'title' => 'Split',
                'description' => 'Split string by delimiter and return the result',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Delimiter',
                        'type' => 'string',
                        'direction' => 0,
                        'default' => ','
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<string>',
                        'direction' => 1,
                    ],
                ]
            ],
            'strreplace' => [
                'name' => 'strreplace',
                'title' => 'Replace',
                'description' => 'Replace all occurrences of search in string with replace',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Search',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Replace',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'trim' => [
                'name' => 'trim',
                'title' => 'Trim',
                'description' => 'Trim whitespace from the beginning and end of string',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'strtolower' => [
                'name' => 'strtolower',
                'title' => 'Lowercase',
                'description' => 'Convert string to lowercase',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'strtoupper' => [
                'name' => 'upper',
                'title' => 'Uppercase',
                'description' => 'Convert string to uppercase',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'strlen' => [
                'name' => 'strlen',
                'title' => 'Length',
                'description' => 'Return the length of string',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'substr' => [
                'name' => 'substr',
                'title' => 'Substring',
                'description' => 'Return part of string starting at position and length',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Start',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Length',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'strpos' => [
                'name' => 'strpos',
                'title' => 'Position',
                'description' => 'Find the position of the first occurrence of a substring in a string',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Search',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'strrev' => [
                'name' => 'reverse',
                'title' => 'Reverse',
                'description' => 'Reverse string',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'str_repeat' => [
                'name' => 'repeat',
                'title' => 'Repeat',
                'description' => 'Repeat string X times',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Times',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'str_contains' => [
                'name' => 'contains',
                'title' => 'Contains',
                'description' => 'Check if string contains substring',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Search',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_email' => [
                'name' => 'validate_email',
                'title' => 'Validate Email',
                'description' => 'Check if email is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'Email',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_url' => [
                'name' => 'validate_url',
                'title' => 'Validate URL',
                'description' => 'Check if URL is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'URL',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_ip' => [
                'name' => 'validate_ip',
                'title' => 'Validate IP',
                'description' => 'Check if IP address is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'IP',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_date' => [
                'name' => 'validate_date',
                'title' => 'Validate Date',
                'description' => 'Check if date is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'Date',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Format',
                        'type' => 'string',
                        'direction' => 0,
                        'default' => "Y-m-d"
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_json' => [
                'name' => 'validate_json',
                'title' => 'Validate JSON',
                'description' => 'Check if JSON is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'JSON',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_base64' => [
                'name' => 'validate_base64',
                'title' => 'Validate Base64',
                'description' => 'Check if Base64 is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'Base64',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_md5' => [
                'name' => 'validate_md5',
                'title' => 'Validate MD5',
                'description' => 'Check if MD5 is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'MD5',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_sha1' => [
                'name' => 'validate_sha1',
                'title' => 'Validate SHA1',
                'description' => 'Check if SHA1 is valid',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'SHA1',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'validate_regex' => [
                'name' => 'validate_regex',
                'title' => 'Validate Regex',
                'description' => 'Check if string matches regex pattern',
                'type' => 'text',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Pattern',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'boolean' => [
            'and' => [
                'name' => 'and',
                'title' => 'And',
                'description' => 'Check if A and B are true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'or' => [
                'name' => 'or',
                'title' => 'Or',
                'description' => 'Check if A or B are true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'not' => [
                'name' => 'not',
                'title' => 'Not',
                'description' => 'Check if A is not true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'xor' => [
                'name' => 'xor',
                'title' => 'Xor',
                'description' => 'Check if A or B are true, but not both',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'nand' => [
                'name' => 'nand',
                'title' => 'Nand',
                'description' => 'Check if A and B are not true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'nor' => [
                'name' => 'nor',
                'title' => 'Nor',
                'description' => 'Check if A or B are not true',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'xnor' => [
                'name' => 'xnor',
                'title' => 'Xnor',
                'description' => 'Check if A and B are true, or both are false',
                'type' => 'logic',
                'ports' => [
                    [
                        'name' => 'A',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'B',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'array' => [
            'array' => [
                'name' => 'array',
                'title' => 'Array',
                'description' => 'Create an array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Items',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_push' => [
                'name' => 'array_push',
                'title' => 'Array Push',
                'description' => 'Push an element onto the end of array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Element',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_pop' => [
                'name' => 'array_pop',
                'title' => 'Array Pop',
                'description' => 'Pop the element off the end of array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_shift' => [
                'name' => 'array_shift',
                'title' => 'Array Shift',
                'description' => 'Shift an element off the beginning of array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_unshift' => [
                'name' => 'array_unshift',
                'title' => 'Array Unshift',
                'description' => 'Prepend one or more elements to the beginning of array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Elements',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_set' => [
                'name' => 'array_set',
                'title' => 'Array Set',
                'description' => 'Set value of array at index',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Index',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Value',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_get' => [
                'name' => 'array_get',
                'title' => 'Array Get',
                'description' => 'Get value of array at index',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Index',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_get_random' => [
                'name' => 'array_get_random',
                'title' => 'Array Get Random',
                'description' => 'Get random value from array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_length' => [
                'name' => 'array_length',
                'title' => 'Array Length',
                'description' => 'Return the number of elements in array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_slice' => [
                'name' => 'array_slice',
                'title' => 'Array Slice',
                'description' => 'Extract a slice of the array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Start',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Length',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_splice' => [
                'name' => 'array_splice',
                'title' => 'Array Splice',
                'description' => 'Remove a portion of the array and replace it with something else',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Start',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Length',
                        'type' => 'number',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Replacement',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_reverse' => [
                'name' => 'array_reverse',
                'title' => 'Array Reverse',
                'description' => 'Return an array with elements in reverse order',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_shuffle' => [
                'name' => 'array_shuffle',
                'title' => 'Array Shuffle',
                'description' => 'Shuffle the elements in the array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_sort' => [
                'name' => 'array_sort',
                'title' => 'Array Sort',
                'description' => 'Sort the array',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'array_sort_by' => [
                'name' => 'array_sort_by',
                'title' => 'Array Sort By',
                'description' => 'Sort the array by key',
                'type' => 'array',
                'ports' => [
                    [
                        'name' => 'Array',
                        'type' => 'array<any>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Key',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'object' => [
            'object' => [
                'name' => 'object',
                'title' => 'Object',
                'description' => 'Create an object',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Properties',
                        'type' => 'array<array<string>>',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'object',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_get' => [
                'name' => 'object_get',
                'title' => 'Object Get',
                'description' => 'Get value of object property',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Property',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_set' => [
                'name' => 'object_set',
                'title' => 'Object Set',
                'description' => 'Set value of object property',
                'type' => 'object',
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
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Property',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Value',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'object',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_keys' => [
                'name' => 'object_keys',
                'title' => 'Object Keys',
                'description' => 'Return an array of object keys',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<string>',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_values' => [
                'name' => 'object_values',
                'title' => 'Object Values',
                'description' => 'Return an array of object values',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'array<any>',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_length' => [
                'name' => 'object_length',
                'title' => 'Object Length',
                'description' => 'Return the number of properties in object',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_has' => [
                'name' => 'object_has',
                'title' => 'Object Has',
                'description' => 'Check if object has property',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Property',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'boolean',
                        'direction' => 1,
                    ],
                ]
            ],
            'object_delete' => [
                'name' => 'object_delete',
                'title' => 'Object Delete',
                'description' => 'Delete property from object',
                'type' => 'object',
                'ports' => [
                    [
                        'name' => 'Object',
                        'type' => 'object',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Property',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'object',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'filesystem' => [
            'open_file' => [
                'name' => 'open_file',
                'title' => 'Open File',
                'description' => 'Open file and return file handle',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                ]
            ],
            'save_file' => [
                'name' => 'save_file',
                'title' => 'Save File',
                'description' => 'Save data to file',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Data',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                ]
            ],
            'delete_file' => [
                'name' => 'delete_file',
                'title' => 'Delete File',
                'description' => 'Delete file',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                ]
            ],
            'open_csv' => [
                'name' => 'open_csv',
                'title' => 'Open CSV',
                'description' => 'Open CSV file and return array of rows',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Delimiter',
                        'type' => 'string',
                        'direction' => 0,
                        'default' => ','
                    ],
                    [
                        'name' => 'Rows',
                        'type' => 'array<array<string>>',
                        'direction' => 1,
                    ]
                ]
            ],
            'save_csv' => [
                'name' => 'save_csv',
                'title' => 'Save CSV',
                'description' => 'Save array of rows to CSV file',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Delimiter',
                        'type' => 'string',
                        'direction' => 0,
                        'default' => ','
                    ],
                    [
                        'name' => 'Rows',
                        'type' => 'array<array<string>>',
                        'direction' => 0,
                    ]
                ]
            ],
            'file_url' => [
                'name' => 'file_url',
                'title' => 'File URL',
                'description' => 'Return URL of file',
                'type' => 'filesystem',
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
                        'name' => 'Filename',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ]
                ]
            ],
            'file_mime' => [
                'name' => 'file_mime',
                'title' => 'File MIME',
                'description' => 'Return MIME type of file',
                'type' => 'filesystem',
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
                        'name' => 'File',
                        'type' => 'resource',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ]
                ]
            ],
            'file_size' => [
                'name' => 'file_size',
                'title' => 'File Size',
                'description' => 'Return size of file in bytes',
                'type' => 'filesystem',
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
                        'name' => 'File',
                        'type' => 'resource',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'number',
                        'direction' => 1,
                    ]
                ]
            ],
        ],
        'http' => [
            'http_get' => [
                'name' => 'http_get',
                'title' => 'HTTP GET',
                'description' => 'Send HTTP GET request and return response',
                'type' => 'http',
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
                        'name' => 'URL',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Headers',
                        'type' => 'array<string>',
                        'direction' => 0,
                        'default' => '[]'
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ]
                ]
            ],
            'http_post' => [
                'name' => 'http_post',
                'title' => 'HTTP POST',
                'description' => 'Send HTTP POST request and return response',
                'type' => 'http',
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
                        'name' => 'URL',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Headers',
                        'type' => 'array<string>',
                        'direction' => 0,
                        'default' => '[]'
                    ],
                    [
                        'name' => 'Data',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ]
                ]
            ],
            'http_put' => [
                'name' => 'http_put',
                'title' => 'HTTP PUT',
                'description' => 'Send HTTP PUT request and return response',
                'type' => 'http',
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
                        'name' => 'URL',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Headers',
                        'type' => 'array<string>',
                        'direction' => 0,
                        'default' => '[]'
                    ],
                    [
                        'name' => 'Data',
                        'type' => 'any',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ]
                ]
            ],
            'http_delete' => [
                'name' => 'http_delete',
                'title' => 'HTTP DELETE',
                'description' => 'Send HTTP DELETE request and return response',
                'type' => 'http',
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
                        'name' => 'URL',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Headers',
                        'type' => 'array<string>',
                        'direction' => 0,
                        'default' => '[]'
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'any',
                        'direction' => 1,
                    ]
                ]
            ],
        ],
        'datetime' => [
            'datetime' => [
                'name' => 'datetime',
                'title' => 'Datetime',
                'description' => 'Create a new DateTime object',
                'type' => 'datetime',
                'ports' => [
                    [
                        'name' => 'Date',
                        'type' => 'string',
                        'direction' => 0,
                        'default' => 'now'
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'datetime',
                        'direction' => 1,
                    ],
                ]
            ],
            'datetime_format' => [
                'name' => 'datetime_format',
                'title' => 'Datetime Format',
                'description' => 'Format DateTime object',
                'type' => 'datetime',
                'ports' => [
                    [
                        'name' => 'Datetime',
                        'type' => 'datetime',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Format',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'datetime_add' => [
                'name' => 'datetime_add',
                'title' => 'Datetime Add',
                'description' => 'Add interval to DateTime object',
                'type' => 'datetime',
                'ports' => [
                    [
                        'name' => 'Datetime',
                        'type' => 'datetime',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Interval',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'datetime',
                        'direction' => 1,
                    ],
                ]
            ],
            'datetime_diff' => [
                'name' => 'datetime_diff',
                'title' => 'Datetime Diff',
                'description' => 'Return difference between two DateTime objects',
                'type' => 'datetime',
                'ports' => [
                    [
                        'name' => 'Datetime 1',
                        'type' => 'datetime',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Datetime 2',
                        'type' => 'datetime',
                        'direction' => 0,
                        'default' => 'now'
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
        'cryptography' => [
            'md5' => [
                'name' => 'md5',
                'title' => 'MD5',
                'description' => 'Calculate MD5 hash',
                'type' => 'cryptography',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'sha1' => [
                'name' => 'sha1',
                'title' => 'SHA1',
                'description' => 'Calculate SHA1 hash',
                'type' => 'cryptography',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'base64_encode' => [
                'name' => 'base64_encode',
                'title' => 'Base64 Encode',
                'description' => 'Encode string to Base64',
                'type' => 'cryptography',
                'ports' => [
                    [
                        'name' => 'String',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
            'base64_decode' => [
                'name' => 'base64_decode',
                'title' => 'Base64 Decode',
                'description' => 'Decode Base64 to string',
                'type' => 'cryptography',
                'ports' => [
                    [
                        'name' => 'Base64',
                        'type' => 'string',
                        'direction' => 0,
                    ],
                    [
                        'name' => 'Result',
                        'type' => 'string',
                        'direction' => 1,
                    ],
                ]
            ],
        ],
    ],
    'database' => [
        'operations' => [
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
            ],
        ]
    ],
];
