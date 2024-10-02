( function( blocks, editor, components, i18n, element ) {
    const el = element.createElement;
    const { TextControl, SelectControl } = components;

    // Fetch bookmakers from the WordPress REST API
    async function fetchBookmakers() {
        const response = await fetch('/wp-json/odds/v1/bookmakers'); // Create a REST endpoint for bookmakers
        return response.json();
    }

    blocks.registerBlockType( 'odds/comparison', {
        title: i18n.__('Odds Comparison'),
        icon: 'chart-line',
        category: 'widgets',
        attributes: {
            bookmakers: {
                type: 'array',
                default: []
            }
        },
        edit: function( props ) {
            const { attributes, setAttributes } = props;

            const [bookmakers, setBookmakers] = React.useState([]);
            const [isLoading, setIsLoading] = React.useState(true);

            // Fetch bookmakers when the block is mounted
            React.useEffect(() => {
                fetchBookmakers().then(data => {
                    setBookmakers(data);
                    setIsLoading(false);
                });
            }, []);

            // Render loading state or select control
            if (isLoading) {
                return el('p', {}, 'Loading bookmakers...');
            }

            return el(
                'div',
                { className: 'odds-comparison-block' },
                el( SelectControl, {
                    label: 'Select Bookmakers',
                    multiple: true,
                    value: attributes.bookmakers,
                    options: bookmakers.map(bookmaker => ({
                        label: bookmaker.label,
                        value: bookmaker.value
                    })),
                    onChange: (value) => setAttributes({ bookmakers: value }),
                } )
            );
        },
        save: function() {
            return null;  // Handled by render_callback in PHP
        }
    } );
} )(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
);
