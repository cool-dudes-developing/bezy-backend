const dom = {
    tag: 'div',
    innerContent: '',
    attrs: {
        id: 'Dom',
        style:
            'display:flex;' +
            'flex-direction:column;' +
            'align-items:stretch;' +
            'justify-content:start;' +
            'gap:0px;' +
            'flex-grow:1;' +
            'width:auto;' +
            'height:auto;' +
            'cwidth:fill;' +
            'cheight:fill;' +
            'background-color:black;' +
            'color:black;' +
            'border-style:solid;' +
            'border-width:1px;' +
            'border-radius:0px;' +
            'padding-left:5px;' +
            'padding-right:5px;' +
            'padding-top:5px;' +
            'padding-bottom:5px;' +
            'padding-x:5px;' +
            'padding-y:5px;',
        // class: 'border flex flex-col grow justify-items-stretch'
    },
    children: [
        {
            tag: 'div',
            innerContent: 'Test div',
            attrs: {
                id: 'testDiv',
                style:
                    'display:flex;' +
                    'flex-direction:column;' +
                    'align-items:stretch;' +
                    'justify-content:start;' +
                    'gap:0px;' +
                    'flex-grow:1;' +
                    'width:auto;' +
                    'height:auto;' +
                    'cwidth:fill;' +
                    'cheight:fill;' +
                    'background-color:#69e5f8;' +
                    'color:black;' +
                    'border-style:solid;' +
                    'border-width:1px;' +
                    'border-radius:0px;' +
                    'padding-left:5px;' +
                    'padding-right:5px;' +
                    'padding-top:5px;' +
                    'padding-bottom:5px;' +
                    'padding-x:5px;' +
                    'padding-y:5px;',
            },
            children: [],
        },
    ],
}

export default dom
