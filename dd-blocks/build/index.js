( function() {
	const { registerBlockType } = wp.blocks;
	const { __ } = wp.i18n;
	const { createElement: el, Fragment } = wp.element;
	const { InspectorControls, MediaUpload, RichText, useBlockProps, useInnerBlocksProps } = wp.blockEditor;
	const { BaseControl, Button, FocalPointPicker, PanelBody, PanelRow, SelectControl, TextControl, ToggleControl } = wp.components;

	const FRAMEWORK_CATEGORY = 'duck-diver-framework';
	const MARGIN_FIELDS = [
		{
			attribute: 'blockMarginTop',
			label: __( 'Margin top (px)', 'dd_theme' ),
			allowNegative: true,
		},
		{
			attribute: 'blockMarginRight',
			label: __( 'Margin right (px)', 'dd_theme' ),
			allowNegative: true,
		},
		{
			attribute: 'blockMarginBottom',
			label: __( 'Margin bottom (px)', 'dd_theme' ),
			allowNegative: true,
		},
		{
			attribute: 'blockMarginLeft',
			label: __( 'Margin left (px)', 'dd_theme' ),
			allowNegative: true,
		},
	];
	const PADDING_FIELDS = [
		{
			attribute: 'blockPaddingTop',
			label: __( 'Padding top (px)', 'dd_theme' ),
			allowNegative: false,
		},
		{
			attribute: 'blockPaddingRight',
			label: __( 'Padding right (px)', 'dd_theme' ),
			allowNegative: false,
		},
		{
			attribute: 'blockPaddingBottom',
			label: __( 'Padding bottom (px)', 'dd_theme' ),
			allowNegative: false,
		},
		{
			attribute: 'blockPaddingLeft',
			label: __( 'Padding left (px)', 'dd_theme' ),
			allowNegative: false,
		},
	];
	const SPACING_ATTRIBUTE_DEFAULTS = [ ...MARGIN_FIELDS, ...PADDING_FIELDS ].reduce(
		function( attributes, field ) {
			attributes[ field.attribute ] = {
				type: 'number',
				default: null,
			};
			return attributes;
		},
		{}
	);
	const BLOCK_SUPPORTS = {
		html: false,
		customClassName: true,
		anchor: true,
	};
	const CONTAINER_SUPPORTS = {
		...BLOCK_SUPPORTS,
		color: {
			background: true,
		},
	};
	const CONTAINER_ALLOWED_BLOCKS = [ 'dd/container', 'dd/row', 'dd/wrapper' ];
	const ROW_ALLOWED_BLOCKS = [ 'dd/column' ];
	const CARD_ALLOWED_BLOCKS = [ 'dd/card-image-top', 'dd/card-header', 'dd/card-body', 'dd/card-footer' ];
	const CONTAINER_TEMPLATE = [
		[
			'dd/row',
			{},
			[
				[ 'dd/column', {} ],
				[ 'dd/column', {} ],
				[ 'dd/column', {} ],
			],
		],
	];
	const ROW_TEMPLATE = [
		[ 'dd/column', {} ],
	];
	const CARD_TEMPLATE = [
		[ 'dd/card-header', {} ],
		[ 'dd/card-body', {} ],
	];
	const DEFAULT_COLUMN_CLASSES = 'col';
	const WRAPPER_TAG_OPTIONS = [
		{ label: '<div>', value: 'div' },
		{ label: '<section>', value: 'section' },
		{ label: '<article>', value: 'article' },
		{ label: '<aside>', value: 'aside' },
		{ label: '<figure>', value: 'figure' },
	];
	const BUTTON_STYLE_OPTIONS = [
		{ label: __( 'Primary', 'dd_theme' ), value: 'btn-primary' },
		{ label: __( 'Secondary', 'dd_theme' ), value: 'btn-secondary' },
		{ label: __( 'Success', 'dd_theme' ), value: 'btn-success' },
		{ label: __( 'Danger', 'dd_theme' ), value: 'btn-danger' },
		{ label: __( 'Warning', 'dd_theme' ), value: 'btn-warning' },
		{ label: __( 'Info', 'dd_theme' ), value: 'btn-info' },
		{ label: __( 'Light', 'dd_theme' ), value: 'btn-light' },
		{ label: __( 'Dark', 'dd_theme' ), value: 'btn-dark' },
		{ label: __( 'Link', 'dd_theme' ), value: 'btn-link' },
	];
	const BUTTON_SIZE_OPTIONS = [
		{ label: __( 'Default', 'dd_theme' ), value: '' },
		{ label: __( 'Large', 'dd_theme' ), value: 'btn-lg' },
		{ label: __( 'Small', 'dd_theme' ), value: 'btn-sm' },
	];
	const BUTTON_ICON_OPTIONS = [
		{ label: __( 'None', 'dd_theme' ), value: '' },
		{ label: __( 'Arrow Right', 'dd_theme' ), value: 'arrow-right' },
		{ label: __( 'Arrow Left', 'dd_theme' ), value: 'arrow-left' },
		{ label: __( 'Download', 'dd_theme' ), value: 'download' },
		{ label: __( 'External', 'dd_theme' ), value: 'box-arrow-up-right' },
		{ label: __( 'Chevron Right', 'dd_theme' ), value: 'chevron-right' },
		{ label: __( 'Phone', 'dd_theme' ), value: 'phone' },
		{ label: __( 'Star', 'dd_theme' ), value: 'star' },
		{ label: __( 'Info Circle', 'dd_theme' ), value: 'info-circle' },
	];
	const BACKGROUND_REPEAT_OPTIONS = [
		{ label: __( 'Default', 'dd_theme' ), value: '' },
		{ label: 'no-repeat', value: 'no-repeat' },
		{ label: 'repeat', value: 'repeat' },
		{ label: 'repeat-x', value: 'repeat-x' },
		{ label: 'repeat-y', value: 'repeat-y' },
		{ label: 'space', value: 'space' },
		{ label: 'round', value: 'round' },
	];
	const BACKGROUND_ATTACHMENT_OPTIONS = [
		{ label: __( 'Default', 'dd_theme' ), value: '' },
		{ label: 'scroll', value: 'scroll' },
		{ label: 'fixed', value: 'fixed' },
		{ label: 'local', value: 'local' },
	];

	function normalizeSpacingValue( value, allowNegative ) {
		if ( value === '' || value === null || typeof value === 'undefined' ) {
			return null;
		}

		const numberValue = parseInt( value, 10 );

		if ( Number.isNaN( numberValue ) ) {
			return null;
		}

		if ( ! allowNegative && numberValue < 0 ) {
			return 0;
		}

		return numberValue;
	}

	function getSpacingStyle( attributes ) {
		const styles = {};
		const styleMap = {
			blockPaddingTop: 'paddingTop',
			blockPaddingRight: 'paddingRight',
			blockPaddingBottom: 'paddingBottom',
			blockPaddingLeft: 'paddingLeft',
			blockMarginTop: 'marginTop',
			blockMarginRight: 'marginRight',
			blockMarginBottom: 'marginBottom',
			blockMarginLeft: 'marginLeft',
		};

		Object.keys( styleMap ).forEach( function( attributeName ) {
			if ( typeof attributes[ attributeName ] === 'number' && ! Number.isNaN( attributes[ attributeName ] ) ) {
				styles[ styleMap[ attributeName ] ] = attributes[ attributeName ] + 'px';
			}
		} );

		return Object.keys( styles ).length ? styles : undefined;
	}

	function getBackgroundImageStyle( attributes, existingStyle ) {
		const style = {
			...( existingStyle || {} ),
		};

		if ( attributes.backgroundImage && attributes.backgroundImage.url ) {
			style.backgroundImage = 'url(' + attributes.backgroundImage.url + ')';

			if ( attributes.backgroundSize ) {
				style.backgroundSize = attributes.backgroundSize;
			}

			if ( attributes.backgroundRepeat ) {
				style.backgroundRepeat = attributes.backgroundRepeat;
			}

			if (
				attributes.backgroundPosition &&
				typeof attributes.backgroundPosition.x === 'number' &&
				typeof attributes.backgroundPosition.y === 'number'
			) {
				style.backgroundPosition =
					Math.round( attributes.backgroundPosition.x * 100 ) +
					'% ' +
					Math.round( attributes.backgroundPosition.y * 100 ) +
					'%';
			}

			if ( attributes.backgroundAttachment ) {
				style.backgroundAttachment = attributes.backgroundAttachment;
			}
		}

		return Object.keys( style ).length ? style : undefined;
	}

	function normalizeWrapperTag( value ) {
		const allowedTagNames = WRAPPER_TAG_OPTIONS.map( function( option ) {
			return option.value;
		} );

		return allowedTagNames.includes( value ) ? value : 'div';
	}

	function getButtonClasses( attributes ) {
		const baseStyle = attributes.buttonStyle || 'btn-primary';
		const variantClass = attributes.outline ? baseStyle.replace( /^btn-/, 'btn-outline-' ) : baseStyle;

		return [
			'btn',
			variantClass,
			attributes.buttonSize || '',
			attributes.block ? 'btn-block' : '',
		]
			.filter( Boolean )
			.join( ' ' );
	}

	function renderButtonIcon( icon, className ) {
		if ( ! icon ) {
			return null;
		}

		return el(
			'svg',
			{
				className: [ 'btn-icon', icon, className || '' ].filter( Boolean ).join( ' ' ),
				viewBox: '0 0 16 16',
				width: '1em',
				height: '1em',
				fill: 'currentColor',
				'aria-hidden': true,
				focusable: false,
			},
			el( 'use', { href: '#icon-' + icon } )
		);
	}

	function renderSpacingFieldControls( fields, attributes, setAttributes ) {
		return fields.map( function( field ) {
			return el( TextControl, {
				key: field.attribute,
				label: field.label,
				type: 'number',
				step: '1',
				value: typeof attributes[ field.attribute ] === 'number' ? attributes[ field.attribute ] : '',
				onChange: function( value ) {
					setAttributes( {
						[ field.attribute ]: normalizeSpacingValue( value, field.allowNegative ),
					} );
				},
			} );
		} );
	}

	function renderSpacingPanels( attributes, setAttributes ) {
		return el(
			InspectorControls,
			null,
			el(
				PanelBody,
				{
					title: __( 'Block Margin', 'dd_theme' ),
					initialOpen: false,
				},
				renderSpacingFieldControls( MARGIN_FIELDS, attributes, setAttributes )
			),
			el(
				PanelBody,
				{
					title: __( 'Block Padding', 'dd_theme' ),
					initialOpen: false,
				},
				renderSpacingFieldControls( PADDING_FIELDS, attributes, setAttributes )
			)
		);
	}

	function getCardImageAttributes( media ) {
		if ( ! media || ! media.url ) {
			return {
				imageId: 0,
				imageUrl: '',
				imageAlt: '',
				imageWidth: 0,
				imageHeight: 0,
			};
		}

		return {
			imageId: media.id || 0,
			imageUrl: media.url,
			imageAlt: media.alt || '',
			imageWidth: media.width || 0,
			imageHeight: media.height || 0,
		};
	}

	function ContainerEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: attributes.fluid ? 'container-fluid' : 'container',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps(
			blockProps,
			{
				allowedBlocks: CONTAINER_ALLOWED_BLOCKS,
				template: CONTAINER_TEMPLATE,
				templateLock: false,
			}
		);

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Container Settings', 'dd_theme' ), initialOpen: true },
					el( ToggleControl, {
						label: __( 'Use fluid container', 'dd_theme' ),
						checked: !! attributes.fluid,
						onChange: function( fluid ) {
							setAttributes( { fluid: fluid } );
						},
					} )
				)
			),
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function ContainerSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: attributes.fluid ? 'container-fluid' : 'container',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function RowEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: 'row',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps(
			blockProps,
			{
				allowedBlocks: ROW_ALLOWED_BLOCKS,
				template: ROW_TEMPLATE,
				templateLock: false,
				orientation: 'horizontal',
			}
		);

		return el(
			Fragment,
			null,
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function RowSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'row',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function WrapperEdit( { attributes, setAttributes } ) {
		const tagName = normalizeWrapperTag( attributes.tagName );
		const blockProps = useBlockProps( {
			style: getBackgroundImageStyle( attributes, getSpacingStyle( attributes ) ),
		} );
		const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: false } );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Wrapper Settings', 'dd_theme' ), initialOpen: true },
					el( SelectControl, {
						label: __( 'HTML element', 'dd_theme' ),
						value: tagName,
						options: WRAPPER_TAG_OPTIONS,
						onChange: function( value ) {
							setAttributes( { tagName: normalizeWrapperTag( value ) } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Background Image Settings', 'dd_theme' ), initialOpen: false },
					el(
						PanelRow,
						null,
						el(
							BaseControl,
							{ className: 'w-100' },
							el( MediaUpload, {
								onSelect: function( media ) {
									setAttributes( {
										backgroundImage: media
											? {
												id: media.id,
												url: media.url,
												alt: media.alt || '',
												width: media.width,
												height: media.height,
											}
											: null,
									} );
								},
								allowedTypes: [ 'image' ],
								value: attributes.backgroundImage && attributes.backgroundImage.id ? attributes.backgroundImage.id : 0,
								render: function( mediaUploadProps ) {
									return el(
										Fragment,
										null,
										attributes.backgroundImage && attributes.backgroundImage.url
											? el(
												Fragment,
												null,
												el( 'img', {
													src: attributes.backgroundImage.url,
													alt: attributes.backgroundImage.alt || '',
													style: {
														display: 'block',
														width: '100%',
														marginBottom: '0.75rem',
														borderRadius: '0.25rem',
													},
												} ),
												el(
													'div',
													{ className: 'components-flex components-h-stack', style: { gap: '0.5rem' } },
													el(
														Button,
														{
															variant: 'primary',
															onClick: mediaUploadProps.open,
														},
														__( 'Replace image', 'dd_theme' )
													),
													el(
														Button,
														{
															variant: 'secondary',
															onClick: function() {
																setAttributes( {
																	backgroundImage: null,
																	backgroundSize: '',
																	backgroundRepeat: '',
																	backgroundPosition: {},
																	backgroundAttachment: '',
																} );
															},
														},
														__( 'Clear image', 'dd_theme' )
													)
												)
											)
											: el(
												Button,
												{
													variant: 'secondary',
													onClick: mediaUploadProps.open,
												},
												__( 'Select image', 'dd_theme' )
											)
									);
								},
							} )
						)
					),
					el(
						PanelRow,
						null,
						el( SelectControl, {
							label: __( 'Background size', 'dd_theme' ),
							value: attributes.backgroundSize || '',
							options: [
								{ label: __( 'Default', 'dd_theme' ), value: '' },
								{ label: 'cover', value: 'cover' },
								{ label: 'contain', value: 'contain' },
								{ label: __( 'Custom', 'dd_theme' ), value: 'custom' },
							],
							onChange: function( value ) {
								if ( 'custom' === value ) {
									setAttributes( { backgroundSize: attributes.backgroundSize && 'cover' !== attributes.backgroundSize && 'contain' !== attributes.backgroundSize ? attributes.backgroundSize : '100% auto' } );
									return;
								}

								setAttributes( { backgroundSize: value } );
							},
						} )
					),
					attributes.backgroundSize &&
					'cover' !== attributes.backgroundSize &&
					'contain' !== attributes.backgroundSize
						? el(
							PanelRow,
							null,
							el( TextControl, {
								label: __( 'Custom background size', 'dd_theme' ),
								value: attributes.backgroundSize,
								onChange: function( value ) {
									setAttributes( { backgroundSize: value } );
								},
							} )
						)
						: null,
					el(
						PanelRow,
						null,
						el( SelectControl, {
							label: __( 'Background repeat', 'dd_theme' ),
							value: attributes.backgroundRepeat || '',
							options: BACKGROUND_REPEAT_OPTIONS,
							onChange: function( value ) {
								setAttributes( { backgroundRepeat: value } );
							},
						} )
					),
					attributes.backgroundImage && attributes.backgroundImage.url
						? el(
							PanelRow,
							null,
							el( FocalPointPicker, {
								url: attributes.backgroundImage.url,
								dimensions:
									attributes.backgroundImage.width && attributes.backgroundImage.height
										? {
											width: attributes.backgroundImage.width,
											height: attributes.backgroundImage.height,
										}
										: undefined,
								value:
									attributes.backgroundPosition &&
									typeof attributes.backgroundPosition.x === 'number' &&
									typeof attributes.backgroundPosition.y === 'number'
										? attributes.backgroundPosition
										: { x: 0.5, y: 0.5 },
								onChange: function( value ) {
									setAttributes( { backgroundPosition: value } );
								},
							} )
						)
						: null,
					el(
						PanelRow,
						null,
						el( SelectControl, {
							label: __( 'Background attachment', 'dd_theme' ),
							value: attributes.backgroundAttachment || '',
							options: BACKGROUND_ATTACHMENT_OPTIONS,
							onChange: function( value ) {
								setAttributes( { backgroundAttachment: value } );
							},
						} )
					)
				)
			),
			renderSpacingPanels( attributes, setAttributes ),
			el( tagName, innerBlocksProps )
		);
	}

	function WrapperSave( { attributes } ) {
		const tagName = normalizeWrapperTag( attributes.tagName );
		const blockProps = useBlockProps.save( {
			style: getBackgroundImageStyle( attributes, getSpacingStyle( attributes ) ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( tagName, innerBlocksProps );
	}

	function ColumnEdit( { attributes, setAttributes } ) {
		const columnClasses = ( attributes.columnClasses || DEFAULT_COLUMN_CLASSES ).trim() || DEFAULT_COLUMN_CLASSES;
		const blockProps = useBlockProps( {
			className: columnClasses,
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: false } );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Column Settings', 'dd_theme' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Bootstrap column classes', 'dd_theme' ),
						help: __( 'Examples: col, col-md-6, col-lg-4', 'dd_theme' ),
						value: columnClasses,
						onChange: function( value ) {
							setAttributes( { columnClasses: value.trim() || DEFAULT_COLUMN_CLASSES } );
						},
					} )
				)
			),
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function ColumnSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: ( attributes.columnClasses || DEFAULT_COLUMN_CLASSES ).trim() || DEFAULT_COLUMN_CLASSES,
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function ButtonEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: getButtonClasses( attributes ),
			style: getSpacingStyle( attributes ),
			href: attributes.url || undefined,
			target: attributes.opensInNewTab ? '_blank' : undefined,
			rel: attributes.opensInNewTab ? 'noopener noreferrer' : undefined,
			role: 'button',
			onClick: function( event ) {
				event.preventDefault();
			},
		} );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Button Settings', 'dd_theme' ), initialOpen: true },
					el( SelectControl, {
						label: __( 'Button style', 'dd_theme' ),
						value: attributes.buttonStyle || 'btn-primary',
						options: BUTTON_STYLE_OPTIONS,
						onChange: function( value ) {
							setAttributes( { buttonStyle: value } );
						},
					} ),
					el( ToggleControl, {
						label: __( 'Outline style', 'dd_theme' ),
						checked: !! attributes.outline,
						onChange: function( outline ) {
							setAttributes( { outline: outline } );
						},
					} ),
					el( ToggleControl, {
						label: __( 'Full-width button', 'dd_theme' ),
						checked: !! attributes.block,
						onChange: function( block ) {
							setAttributes( { block: block } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Button size', 'dd_theme' ),
						value: attributes.buttonSize || '',
						options: BUTTON_SIZE_OPTIONS,
						onChange: function( value ) {
							setAttributes( { buttonSize: value } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Button Icon', 'dd_theme' ),
						value: attributes.icon || '',
						options: BUTTON_ICON_OPTIONS,
						onChange: function( value ) {
							setAttributes( { icon: value } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Icon Position', 'dd_theme' ),
						value: attributes.iconPosition || 'before',
						options: [
							{ label: __( 'Before Text', 'dd_theme' ), value: 'before' },
							{ label: __( 'After Text', 'dd_theme' ), value: 'after' },
						],
						onChange: function( value ) {
							setAttributes( { iconPosition: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Button URL', 'dd_theme' ),
						value: attributes.url || '',
						onChange: function( value ) {
							setAttributes( { url: value } );
						},
					} ),
					el( ToggleControl, {
						label: __( 'Open link in new window', 'dd_theme' ),
						checked: !! attributes.opensInNewTab,
						onChange: function( opensInNewTab ) {
							setAttributes( { opensInNewTab: opensInNewTab } );
						},
					} )
				)
			),
			renderSpacingPanels( attributes, setAttributes ),
			el(
				'a',
				blockProps,
				[
					attributes.icon && 'before' === attributes.iconPosition ? renderButtonIcon( attributes.icon, 'mr-2' ) : null,
					el( RichText, {
						tagName: 'span',
						className: 'dd-button-text',
						value: attributes.text,
						allowedFormats: [],
						withoutInteractiveFormatting: true,
						placeholder: __( 'Button text', 'dd_theme' ),
						onChange: function( value ) {
							setAttributes( { text: value } );
						},
					} ),
					attributes.icon && 'after' === attributes.iconPosition ? renderButtonIcon( attributes.icon, 'ml-2' ) : null,
				]
			)
		);
	}

	function ButtonSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: getButtonClasses( attributes ),
			style: getSpacingStyle( attributes ),
			href: attributes.url || undefined,
			target: attributes.opensInNewTab ? '_blank' : undefined,
			rel: attributes.opensInNewTab ? 'noopener noreferrer' : undefined,
			role: 'button',
		} );

		return el(
			'a',
			blockProps,
			[
				attributes.icon && 'before' === attributes.iconPosition ? renderButtonIcon( attributes.icon, 'mr-2' ) : null,
				el( RichText.Content, {
					tagName: 'span',
					className: 'dd-button-text',
					value: attributes.text,
				} ),
				attributes.icon && 'after' === attributes.iconPosition ? renderButtonIcon( attributes.icon, 'ml-2' ) : null,
			]
		);
	}

	function CardEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: 'card',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps(
			blockProps,
			{
				allowedBlocks: CARD_ALLOWED_BLOCKS,
				template: CARD_TEMPLATE,
				templateLock: false,
			}
		);

		return el(
			Fragment,
			null,
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function CardSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'card',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function CardHeaderEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: 'card-header',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: false } );

		return el(
			Fragment,
			null,
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function CardHeaderSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'card-header',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function CardImageTopEdit( { attributes, setAttributes } ) {
		const imageProps = {
			className: 'card-img-top',
		};

		if ( attributes.imageUrl ) {
			imageProps.src = attributes.imageUrl;
			imageProps.alt = attributes.imageAlt || '';
		}

		if ( attributes.imageWidth ) {
			imageProps.width = attributes.imageWidth;
		}

		if ( attributes.imageHeight ) {
			imageProps.height = attributes.imageHeight;
		}

		const blockProps = useBlockProps(
			attributes.imageUrl
				? imageProps
				: {
					className: 'card-img-top dd-card-image-top-placeholder',
					style: {
						alignItems: 'center',
						backgroundColor: '#f8f9fa',
						border: '1px dashed #adb5bd',
						display: 'flex',
						justifyContent: 'center',
						minHeight: '160px',
						padding: '1rem',
					},
				}
		);

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Card Image Settings', 'dd_theme' ), initialOpen: true },
					el( MediaUpload, {
						allowedTypes: [ 'image' ],
						value: attributes.imageId || 0,
						onSelect: function( media ) {
							setAttributes( getCardImageAttributes( media ) );
						},
						render: function( mediaUploadProps ) {
							return el(
								PanelRow,
								null,
								el(
									Button,
									{
										variant: attributes.imageUrl ? 'secondary' : 'primary',
										onClick: mediaUploadProps.open,
									},
									attributes.imageUrl ? __( 'Replace image', 'dd_theme' ) : __( 'Select image', 'dd_theme' )
								)
							);
						},
					} ),
					attributes.imageUrl
						? el( TextControl, {
							label: __( 'Alt text', 'dd_theme' ),
							value: attributes.imageAlt || '',
							onChange: function( imageAlt ) {
								setAttributes( { imageAlt: imageAlt } );
							},
						} )
						: null,
					attributes.imageUrl
						? el(
							PanelRow,
							null,
							el(
								Button,
								{
									variant: 'secondary',
									isDestructive: true,
									onClick: function() {
										setAttributes( getCardImageAttributes() );
									},
								},
								__( 'Remove image', 'dd_theme' )
							)
						)
						: null
				)
			),
			attributes.imageUrl
				? el( 'img', blockProps )
				: el(
					'div',
					blockProps,
					el( MediaUpload, {
						allowedTypes: [ 'image' ],
						value: attributes.imageId || 0,
						onSelect: function( media ) {
							setAttributes( getCardImageAttributes( media ) );
						},
						render: function( mediaUploadProps ) {
							return el(
								Button,
								{
									variant: 'primary',
									onClick: mediaUploadProps.open,
								},
								__( 'Select card image', 'dd_theme' )
							);
						},
					} )
				)
		);
	}

	function CardImageTopSave( { attributes } ) {
		if ( ! attributes.imageUrl ) {
			return null;
		}

		const blockProps = useBlockProps.save( {
			className: 'card-img-top',
			src: attributes.imageUrl,
			alt: attributes.imageAlt || '',
			width: attributes.imageWidth || undefined,
			height: attributes.imageHeight || undefined,
		} );

		return el( 'img', blockProps );
	}

	function CardBodyEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: 'card-body',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: false } );

		return el(
			Fragment,
			null,
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function CardBodySave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'card-body',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	function CardFooterEdit( { attributes, setAttributes } ) {
		const blockProps = useBlockProps( {
			className: 'card-footer',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps( blockProps, { templateLock: false } );

		return el(
			Fragment,
			null,
			renderSpacingPanels( attributes, setAttributes ),
			el( 'div', innerBlocksProps )
		);
	}

	function CardFooterSave( { attributes } ) {
		const blockProps = useBlockProps.save( {
			className: 'card-footer',
			style: getSpacingStyle( attributes ),
		} );
		const innerBlocksProps = useInnerBlocksProps.save( blockProps );

		return el( 'div', innerBlocksProps );
	}

	registerBlockType(
		'dd/container',
		{
			apiVersion: 3,
			title: __( 'Container', 'dd_theme' ),
			description: __( 'Bootstrap container block for rows, wrappers, or nested containers.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'screenoptions',
			supports: CONTAINER_SUPPORTS,
			attributes: {
				fluid: {
					type: 'boolean',
					default: false,
				},
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: ContainerEdit,
			save: ContainerSave,
		}
	);

	registerBlockType(
		'dd/row',
		{
			apiVersion: 3,
			title: __( 'Row', 'dd_theme' ),
			description: __( 'Bootstrap row block for columns.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'table-row-after',
			parent: [ 'dd/container', 'dd/wrapper', 'dd/column' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: RowEdit,
			save: RowSave,
		}
	);

	registerBlockType(
		'dd/wrapper',
		{
			apiVersion: 3,
			title: __( 'Wrapper', 'dd_theme' ),
			description: __( 'Generic semantic wrapper for grouped content.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'editor-contract',
			supports: BLOCK_SUPPORTS,
			attributes: {
				tagName: {
					type: 'string',
					default: 'div',
				},
				backgroundImage: {
					type: 'object',
					default: null,
				},
				backgroundSize: {
					type: 'string',
					default: '',
				},
				backgroundRepeat: {
					type: 'string',
					default: '',
				},
				backgroundPosition: {
					type: 'object',
					default: {},
				},
				backgroundAttachment: {
					type: 'string',
					default: '',
				},
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: WrapperEdit,
			save: WrapperSave,
		}
	);

	registerBlockType(
		'dd/column',
		{
			apiVersion: 3,
			title: __( 'Column', 'dd_theme' ),
			description: __( 'Bootstrap column block for nested content.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'columns',
			parent: [ 'dd/row' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				columnClasses: {
					type: 'string',
					default: DEFAULT_COLUMN_CLASSES,
				},
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: ColumnEdit,
			save: ColumnSave,
		}
	);

	registerBlockType(
		'dd/button',
		{
			apiVersion: 3,
			title: __( 'Button (DD)', 'dd_theme' ),
			description: __( 'Bootstrap button link block.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'admin-links',
			supports: BLOCK_SUPPORTS,
			attributes: {
				text: {
					type: 'string',
					source: 'html',
					selector: '.dd-button-text',
					default: 'Button',
				},
				url: {
					type: 'string',
					default: '',
				},
				buttonStyle: {
					type: 'string',
					default: 'btn-primary',
				},
				outline: {
					type: 'boolean',
					default: false,
				},
				block: {
					type: 'boolean',
					default: false,
				},
				buttonSize: {
					type: 'string',
					default: '',
				},
				opensInNewTab: {
					type: 'boolean',
					default: false,
				},
				icon: {
					type: 'string',
					default: '',
				},
				iconPosition: {
					type: 'string',
					default: 'before',
				},
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: ButtonEdit,
			save: ButtonSave,
			deprecated: [
				{
					attributes: {
						text: {
							type: 'string',
							source: 'html',
							selector: 'a',
							default: 'Button',
						},
						url: {
							type: 'string',
							default: '',
						},
						buttonStyle: {
							type: 'string',
							default: 'btn-primary',
						},
						outline: {
							type: 'boolean',
							default: false,
						},
						block: {
							type: 'boolean',
							default: false,
						},
						buttonSize: {
							type: 'string',
							default: '',
						},
						opensInNewTab: {
							type: 'boolean',
							default: false,
						},
						...SPACING_ATTRIBUTE_DEFAULTS,
					},
					save: function( { attributes } ) {
						const blockProps = useBlockProps.save( {
							className: getButtonClasses( attributes ),
							style: getSpacingStyle( attributes ),
							href: attributes.url || undefined,
							target: attributes.opensInNewTab ? '_blank' : undefined,
							rel: attributes.opensInNewTab ? 'noopener noreferrer' : undefined,
							role: 'button',
						} );

						return el( RichText.Content, {
							...blockProps,
							tagName: 'a',
							value: attributes.text,
						} );
					},
				},
			],
		}
	);

	registerBlockType(
		'dd/card',
		{
			apiVersion: 3,
			title: __( 'Card', 'dd_theme' ),
			description: __( 'Bootstrap card container for card sections.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'index-card',
			supports: BLOCK_SUPPORTS,
			attributes: {
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: CardEdit,
			save: CardSave,
		}
	);

	registerBlockType(
		'dd/card-header',
		{
			apiVersion: 3,
			title: __( 'Card Header', 'dd_theme' ),
			description: __( 'Bootstrap card header section.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'heading',
			parent: [ 'dd/card' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: CardHeaderEdit,
			save: CardHeaderSave,
		}
	);

	registerBlockType(
		'dd/card-image-top',
		{
			apiVersion: 3,
			title: __( 'Card Image Top', 'dd_theme' ),
			description: __( 'Bootstrap card top image section.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'format-image',
			parent: [ 'dd/card' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				imageId: {
					type: 'number',
					default: 0,
				},
				imageUrl: {
					type: 'string',
					default: '',
				},
				imageAlt: {
					type: 'string',
					default: '',
				},
				imageWidth: {
					type: 'number',
					default: 0,
				},
				imageHeight: {
					type: 'number',
					default: 0,
				},
			},
			edit: CardImageTopEdit,
			save: CardImageTopSave,
		}
	);

	registerBlockType(
		'dd/card-body',
		{
			apiVersion: 3,
			title: __( 'Card Body', 'dd_theme' ),
			description: __( 'Bootstrap card body section.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'excerpt-view',
			parent: [ 'dd/card' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: CardBodyEdit,
			save: CardBodySave,
		}
	);

	registerBlockType(
		'dd/card-footer',
		{
			apiVersion: 3,
			title: __( 'Card Footer', 'dd_theme' ),
			description: __( 'Bootstrap card footer section.', 'dd_theme' ),
			category: FRAMEWORK_CATEGORY,
			icon: 'editor-kitchensink',
			parent: [ 'dd/card' ],
			supports: BLOCK_SUPPORTS,
			attributes: {
				...SPACING_ATTRIBUTE_DEFAULTS,
			},
			edit: CardFooterEdit,
			save: CardFooterSave,
		}
	);
} )();
