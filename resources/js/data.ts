// Mock backend data
const users = [
    {
        id: 'user1',
        name: 'John Doe',
        email: 'john.doe@example.com',
        projects: [
            {
                id: 'project1',
                user_id: 'user1',
                name: 'Weather App',
                description:
                    'A weather app that shows the weather in your area',
                methods: [
                    {
                        id: 'method1',
                        project_id: 'project1',
                        name: 'Get Weather',
                        blocks: [
                            {
                                id: 'block_start',
                                method_id: 'method1',
                                name: 'Start',
                                top: 10,
                                left: 10,
                                ports: [
                                    {
                                        id: 'port_start',
                                        block_id: 'block_start',
                                        connected_to: 'port_end',
                                        name: 'Start',
                                        type: 'flow',
                                        direction: 'out',
                                    },
                                ],
                            },
                            {
                                id: 'block_end',
                                method_id: 'method1',
                                name: 'End',
                                top: 10,
                                left: 400,
                                ports: [
                                    {
                                        id: 'port_end',
                                        block_id: 'block_end',
                                        connected_to: 'port_start',
                                        name: 'End',
                                        type: 'flow',
                                        direction: 'in',
                                    },
                                    {
                                        id: 'port_end_response',
                                        block_id: 'block_end',
                                        connected_to: 'port1',
                                        name: 'Response',
                                        type: 'data',
                                        direction: 'in',
                                    },
                                ],
                            },
                            {
                                id: 'block1',
                                method_id: 'method1',
                                name: 'Const string',
                                top: 120,
                                left: 200,
                                ports: [
                                    {
                                        id: 'port1',
                                        block_id: 'block1',
                                        connected_to: 'port_end_response',
                                        name: 'String',
                                        type: 'data',
                                        direction: 'out',
                                    },
                                ],
                            },
                            {
                                id: 'block2',
                                method_id: 'method1',
                                name: 'Const int',
                                top: 230,
                                left: 10,
                                ports: [
                                    {
                                        id: 'port2',
                                        block_id: 'block2',
                                        name: 'Int',
                                        type: 'data',
                                        direction: 'out',
                                    },
                                ],
                            },
                        ],
                    },
                ],
                endpoints: [
                    {
                        id: 'endpoint1',
                        project_id: 'project1',
                        name: 'Get Weather',
                        uri: '/weather',
                        method: 'GET',
                        middleware: [
                            {
                                id: 1,
                                name: 'Auth',
                                type: 'auth',
                            },
                        ],
                    },
                ],
            },
        ],
    },
]

const blocks = [
    {
        id: 'template_const_string',
        name: 'Const string',
        ports: [
            {
                id: 'template_const_string_port1',
                name: 'String',
                type: 'data',
                direction: 'out',
            },
        ],
    },
    {
        id: 'template_concat_string',
        name: 'Concat string',
        ports: [
            {
                id: 'template_concat_string_port1',
                name: 'String 1',
                type: 'data',
                direction: 'in',
            },
            {
                id: 'template_concat_string_port2',
                name: 'String 2',
                type: 'data',
                direction: 'in',
            },
            {
                id: 'template_concat_string_port3',
                name: 'String',
                type: 'data',
                direction: 'out',
            },
        ],
    },
]

export default { users, blocks }
