( function ( $, undefined ) {
	/**
	 * postboxManager
	 *
	 * Manages postboxes on the screen.
	 *
	 * @date	25/5/19
	 * @since	ACF 5.8.1
	 *
	 * @param	void
	 * @return	void
	 */
	var postboxManager = new acf.Model( {
		wait: 'prepare',
		priority: 1,
		initialize: function () {
			( acf.get( 'postboxes' ) || [] ).map( acf.newPostbox );
		},
	} );

	/**
	 *  acf.getPostbox
	 *
	 *  Returns a postbox instance.
	 *
	 *  @date	23/9/18
	 *  @since	ACF 5.7.7
	 *
	 *  @param	mixed $el Either a jQuery element or the postbox id.
	 *  @return	object
	 */
	acf.getPostbox = function ( $el ) {
		// allow string parameter
		if ( typeof arguments[ 0 ] == 'string' ) {
			$el = $( '#' + arguments[ 0 ] );
		}

		// return instance
		return acf.getInstance( $el );
	};

	/**
	 *  acf.getPostboxes
	 *
	 *  Returns an array of postbox instances.
	 *
	 *  @date	23/9/18
	 *  @since	ACF 5.7.7
	 *
	 *  @param	void
	 *  @return	array
	 */
	acf.getPostboxes = function () {
		return acf.getInstances( $( '.acf-postbox' ) );
	};

	/**
	 *  acf.newPostbox
	 *
	 *  Returns a new postbox instance for the given props.
	 *
	 *  @date	20/9/18
	 *  @since	ACF 5.7.6
	 *
	 *  @param	object props The postbox properties.
	 *  @return	object
	 */
	acf.newPostbox = function ( props ) {
		return new acf.models.Postbox( props );
	};

	/**
	 *  acf.models.Postbox
	 *
	 *  The postbox model.
	 *
	 *  @date	20/9/18
	 *  @since	ACF 5.7.6
	 *
	 *  @param	void
	 *  @return	void
	 */
	acf.models.Postbox = acf.Model.extend( {
		data: {
			id: '',
			key: '',
			style: 'default',
			label: 'top',
			edit: '',
		},

		setup: function ( props ) {
			// compatibility
			if ( props.editLink ) {
				props.edit = props.editLink;
			}

			// extend data
			$.extend( this.data, props );

			// set $el
			this.$el = this.$postbox();
		},

		$postbox: function () {
			return $( '#' + this.get( 'id' ) );
		},

		$hide: function () {
			return $( '#' + this.get( 'id' ) + '-hide' );
		},

		$hideLabel: function () {
			return this.$hide().parent();
		},

		$hndle: function () {
			return this.$( '> .hndle' );
		},

		$handleActions: function () {
			return this.$( '> .postbox-header .handle-actions' );
		},

		$inside: function () {
			return this.$( '> .inside' );
		},

		isVisible: function () {
			return this.$el.hasClass( 'acf-hidden' );
		},

		isHiddenByScreenOptions: function () {
			return (
				this.$el.hasClass( 'hide-if-js' ) ||
				this.$el.css( 'display' ) == 'none'
			);
		},

		initialize: function () {
			// Add default class.
			this.$el.addClass( 'acf-postbox' );

			// Add field group style class (ignore in block editor).
			if ( acf.get( 'editor' ) !== 'block' ) {
				var style = this.get( 'style' );
				if ( style !== 'default' ) {
					this.$el.addClass( style );
				}
			}

			// Add .inside class.
			this.$inside()
				.addClass( 'acf-fields' )
				.addClass( '-' + this.get( 'label' ) );

			// Append edit link.
			var edit = this.get( 'edit' );
			if ( edit ) {
				var html =
					'<a href="' +
					edit +
					'" class="dashicons dashicons-admin-generic acf-hndle-cog acf-js-tooltip" title="' +
					acf.__( 'Edit field group' ) +
					'"></a>';
				var $handleActions = this.$handleActions();
				if ( $handleActions.length ) {
					$handleActions.prepend( html );
				} else {
					this.$hndle().append( html );
				}
			}

			// Show postbox.
			this.show();
		},

		show: function () {
			// If disabled by screen options, set checked to false and return.
			if ( this.$el.hasClass( 'hide-if-js' ) ) {
				this.$hide().prop( 'checked', false );
				return;
			}

			// Show label.
			this.$hideLabel().show();

			// toggle on checkbox
			this.$hide().prop( 'checked', true );

			// Show postbox
			this.$el.show().removeClass( 'acf-hidden' );

			// Do action.
			acf.doAction( 'show_postbox', this );
		},

		enable: function () {
			acf.enable( this.$el, 'postbox' );
		},

		showEnable: function () {
			this.enable();
			this.show();
		},

		hide: function () {
			// Hide label.
			this.$hideLabel().hide();

			// Hide postbox
			this.$el.hide().addClass( 'acf-hidden' );

			// Do action.
			acf.doAction( 'hide_postbox', this );
		},

		disable: function () {
			acf.disable( this.$el, 'postbox' );
		},

		hideDisable: function () {
			this.disable();
			this.hide();
		},

		html: function ( html ) {
			// Update HTML.
			this.$inside().html( html );

			// Do action.
			acf.doAction( 'append', this.$el );
		},
	} );
} )( jQuery );
