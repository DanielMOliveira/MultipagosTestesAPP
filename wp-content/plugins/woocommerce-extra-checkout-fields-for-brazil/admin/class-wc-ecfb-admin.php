<?php
/**
 * WooCommerce Extra Checkout Fields for Brazil.
 *
 * @package   Extra_Checkout_Fields_For_Brazil_Admin
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @copyright 2013 Claudio Sanches
 */

/**
 * Plugin admin class.
 *
 * @package Extra_Checkout_Fields_For_Brazil_Admin
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Extra_Checkout_Fields_For_Brazil_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since 2.8.0
	 *
	 * @var   object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since 2.9.0
	 *
	 * @var   string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 2.9.0
	 */
	private function __construct() {
		global $woocommerce;

		$this->plugin_slug = Extra_Checkout_Fields_For_Brazil::get_plugin_slug();

		if ( ! Extra_Checkout_Fields_For_Brazil::has_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_fallback_notice' ) );
		} else {
			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ), 59 );

			// Init plugin options form.
			add_action( 'admin_init', array( $this, 'plugin_settings' ) );

			// Custom shop_order details.
			add_filter( 'woocommerce_admin_billing_fields', array( $this, 'shop_order_billing_fields' ) );
			add_filter( 'woocommerce_admin_shipping_fields', array( $this, 'shop_order_shipping_fields' ) );
			add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'order_data_after_billing_address' ) );
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'order_data_after_shipping_address' ) );
			add_action( 'save_post', array( $this, 'save_shop_data_fields' ) );

			// Custom address format.
			if ( version_compare( $woocommerce->version, '2.0.6', '>=' ) ) {
				add_filter( 'woocommerce_customer_meta_fields', array( $this, 'user_edit_fields' ) );
			}
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since  2.8.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @since  2.8.2
	 *
	 * @return string Fallack notice.
	 */
	public function woocommerce_fallback_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Extra Checkout Fields for Brazil depends on %s to work!', $this->plugin_slug ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since  2.8.0
	 *
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Extra_Checkout_Fields_For_Brazil::VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since  2.9.0
	 *
	 * @return null Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		global $woocommerce, $post_type;

		if ( 'shop_order' == $post_type ) {

			// Styles.
			wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Extra_Checkout_Fields_For_Brazil::VERSION );

			// Shop order.
			if ( version_compare( $woocommerce->version, '2.1', '>=' ) ) {
				$shop_order_js = plugins_url( 'assets/js/shop-order.min.js', __FILE__ );
			} else {
				$shop_order_js = plugins_url( 'assets/js/shop-order.old.min.js', __FILE__ );
			}

			wp_enqueue_script( $this->plugin_slug . '-shop-order', $shop_order_js, array( 'jquery' ), Extra_Checkout_Fields_For_Brazil::VERSION, true );

			// Localize strings.
			wp_localize_script(
				$this->plugin_slug . '-shop-order',
				'wcbcf_writepanel_params',
				array(
					'load_message' => __( 'Load the customer extras data?', $this->plugin_slug ),
					'copy_message' => __( 'Also copy the data of number and neighborhood?', $this->plugin_slug )
				)
			);
		}

		if ( $this->plugin_screen_hook_suffix == get_current_screen()->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.min.js', __FILE__ ), array( 'jquery' ), Extra_Checkout_Fields_For_Brazil::VERSION );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since  2.8.0
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'woocommerce',
			__( 'Checkout Fields', $this->plugin_slug ),
			__( 'Checkout Fields', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 2.8.0
	 *
	 * @return void
	 */
	public function display_plugin_admin_page() {
		include_once 'views/admin.php';
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since  2.9.0
	 *
	 * @return void.
	 */
	public function plugin_settings() {
		$option = 'wcbcf_settings';

		// Set Custom Fields cection.
		add_settings_section(
			'options_section',
			__( 'Checkout Custom Fields:', $this->plugin_slug ),
			array( $this, 'section_options_callback' ),
			$option
		);

		// Person Type option.
		add_settings_field(
			'person_type',
			__( 'Display Person Type:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'options_section',
			array(
				'menu' => $option,
				'id' => 'person_type',
				'label' => __( 'If checked show the Person Type option and CPF, Company and CNPJ fields in billing options.', $this->plugin_slug )
			)
		);

		// RG option.
		add_settings_field(
			'rg',
			__( 'Display RG:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'options_section',
			array(
				'menu' => $option,
				'id' => 'rg',
				'label' => __( 'If checked show the RG field in billing options.', $this->plugin_slug )
			)
		);

		// State Registration option.
		add_settings_field(
			'ie',
			__( 'Display State Registration:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'options_section',
			array(
				'menu' => $option,
				'id' => 'ie',
				'label' => __( 'If checked show the State Registration field in billing options.', $this->plugin_slug )
			)
		);
		
		// Nome Fantasia option.
		add_settings_field(
			'fantasia',
			__( 'Nome Fantasia:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'options_section',
			array(
				'menu' => $option,
				'id' => 'fantasia',
				'label' => __( 'Nome Fantasia novo campo.', $this->plugin_slug )
			)
		);

		// Birthdate and Sex option.
		//add_settings_field(
		//	'birthdate_sex',
		//	__( 'Display Birthdate and Sex:', $this->plugin_slug ),
		//	array( $this, 'checkbox_element_callback' ),
		//	$option,
			//'options_section',
			//array(
			//	'menu' => $option,
			//	'id' => 'birthdate_sex',
			//	'label' => __( 'If checked show the Birthdate and Sex field in billing options.', $this->plugin_slug )
		//	)
	//	);

		// Cell Phone option.
		add_settings_field(
			'cell_phone',
			__( 'Display Cell Phone:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'options_section',
			array(
				'menu' => $option,
				'id' => 'cell_phone',
				'label' => __( 'If checked show the Cell Phone field in billing options.', $this->plugin_slug )
			)
		);

		// Set Custom Fields cection.
		add_settings_section(
			'jquery_section',
			__( 'jQuery Options:', $this->plugin_slug ),
			array( $this, 'section_options_callback' ),
			$option
		);

		// Mail Check option.
		add_settings_field(
			'mailcheck',
			__( 'Enable Mail Check:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'jquery_section',
			array(
				'menu' => $option,
				'id' => 'mailcheck',
				'label' => __( 'If checked informs typos in email to users.', $this->plugin_slug )
			)
		);

		// Input Mask option.
		add_settings_field(
			'maskedinput',
			__( 'Enable Input Mask:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'jquery_section',
			array(
				'menu' => $option,
				'id' => 'maskedinput',
				'label' => __( 'If checked create masks fill for in fields of CPF, CNPJ, Birthdate, Phone and Cell Phone.', $this->plugin_slug )
			)
		);

		// Address Autocomplete option.
		add_settings_field(
			'addresscomplete',
			__( 'Enable Address Autocomplete:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'jquery_section',
			array(
				'menu' => $option,
				'id' => 'addresscomplete',
				'label' => __( 'If checked automatically complete the address fields based on the zip code.', $this->plugin_slug )
			)
		);

		// Set Custom Fields cection.
		add_settings_section(
			'validation_section',
			__( 'Validation:', $this->plugin_slug ),
			array( $this, 'section_options_callback' ),
			$option
		);

		// Validate CPF option.
		add_settings_field(
			'validate_cpf',
			__( 'Validate CPF:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'validation_section',
			array(
				'menu' => $option,
				'id' => 'validate_cpf',
				'label' => __( 'Checks if the CPF is valid.', $this->plugin_slug )
			)
		);

		// Validate CPF option.
		add_settings_field(
			'validate_cnpj',
			__( 'Validate CNPJ:', $this->plugin_slug ),
			array( $this, 'checkbox_element_callback' ),
			$option,
			'validation_section',
			array(
				'menu' => $option,
				'id' => 'validate_cnpj',
				'label' => __( 'Checks if the CNPJ is valid.', $this->plugin_slug )
			)
		);

		// Register settings.
		register_setting( $option, $option, array( $this, 'validate_options' ) );
	}

	/**
	 * Section null fallback.
	 *
	 * @return void.
	 */
	public function section_options_callback() {

	}

	/**
	 * Checkbox element fallback.
	 *
	 * @return string Checkbox field.
	 */
	public function checkbox_element_callback( $args ) {
		$menu    = $args['menu'];
		$id      = $args['id'];
		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '0';
		}

		$html = '<input type="checkbox" id="' . $id . '" name="' . $menu . '[' . $id . ']" value="1"' . checked( 1, $current, false ) . '/>';

		if ( isset( $args['label'] ) ) {
			$html .= ' <label for="' . $id . '">' . $args['label'] . '</label>';
		}

		if ( isset( $args['description'] ) ) {
			$html .= '<p class="description">' . $args['description'] . '</p>';
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @param  array $input options to valid.
	 *
	 * @return array        validated options.
	 */
	public function validate_options( $input ) {
		$output = array();

		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = woocommerce_clean( $input[ $key ] );
			}
		}

		return $output;
	}

	/**
	 * Custom shop order billing fields.
	 *
	 * @since  2.9.0
	 *
	 * @param  array $data Default order billing fields.
	 *
	 * @return array       Custom order billing fields.
	 */
	public function shop_order_billing_fields( $data ) {
		global $woocommerce;

		// Get plugin settings.
		$settings = get_option( 'wcbcf_settings' );

		$billing_data['first_name'] = array(
			'label' => __( 'First Name', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['last_name'] = array(
			'label' => __( 'Last Name', $this->plugin_slug ),
			'show'  => false
		);

		if ( isset( $settings['person_type'] ) ) {
			$billing_data['persontype'] = array(
				'type'    => 'select',
				'label'   => __( 'Person type', $this->plugin_slug ),
				'options' => array(
					'0' => __( 'Select', $this->plugin_slug ),
					'1' => __( 'Individuals', $this->plugin_slug ),
					'2' => __( 'Legal Person', $this->plugin_slug )
				)
			);
			$billing_data['cpf'] = array(
				'label' => __( 'CPF', $this->plugin_slug ),
			);
			if ( isset( $settings['rg'] ) ) {
				$billing_data['rg'] = array(
					'label' => __( 'RG', $this->plugin_slug ),
				);
			}
			$billing_data['company'] = array(
				'label' => __( 'Company Name', $this->plugin_slug ),
			);
			$billing_data['cnpj'] = array(
				'label' => __( 'CNPJ', $this->plugin_slug ),
			);
			if ( isset( $settings['ie'] ) ) {
				$billing_data['ie'] = array(
					'label' => __( 'State Registration', $this->plugin_slug ),
				);
			}
		} else {
			$billing_data['company'] = array(
				'label' => __( 'Company', $this->plugin_slug ),
				'show'  => false
			);
		}

		//if ( isset( $settings['birthdate_sex'] ) ) {
		//	$billing_data['birthdate'] = array(
			//	'label' => __( 'Birthdate', $this->plugin_slug )
			//);
			//$billing_data['sex'] = array(
			//	'label' => __( 'Sex', $this->plugin_slug )
		//	);
			
			
	//	}
		$billing_data['fantasia'] = array(
			'label' => __( 'Nome Fantasia', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['address_1'] = array(
			'label' => __( 'Address 1', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['number'] = array(
			'label' => __( 'Number', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['address_2'] = array(
			'label' => __( 'Address 2', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['neighborhood'] = array(
			'label' => __( 'Neighborhood', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['city'] = array(
			'label' => __( 'City', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['state'] = array(
			'label' => __( 'State', $this->plugin_slug ),
			'show'  => false
		);
		$billing_data['country'] = array(
			'label'   => __( 'Country', $this->plugin_slug ),
			'show'    => false,
			'type'    => 'select',
			'options' => array(
				'' => __( 'Select a country&hellip;', $this->plugin_slug )
			) + $woocommerce->countries->get_allowed_countries()
		);
		$billing_data['postcode'] = array(
			'label' => __( 'Postcode', $this->plugin_slug ),
			'show'  => false
		);

		$billing_data['phone'] = array(
			'label' => __( 'Phone', $this->plugin_slug ),
		);

		if ( isset( $settings['cell_phone'] ) ) {
			$billing_data['cellphone'] = array(
				'label' => __( 'Cell Phone', $this->plugin_slug ),
			);
		}

		$billing_data['email'] = array(
			'label' => __( 'Email', $this->plugin_slug ),
		);

		return apply_filters( 'wcbcf_admin_billing_fields', $billing_data );
	}

	/**
	 * Custom shop order shipping fields.
	 *
	 * @param  array $data Default order shipping fields.
	 *
	 * @return array       Custom order shipping fields.
	 */
	public function shop_order_shipping_fields( $data ) {
		global $woocommerce;

		$shipping_data['first_name'] = array(
			'label' => __( 'First Name', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['last_name'] = array(
			'label' => __( 'Last Name', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['company'] = array(
			'label' => __( 'Company', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['address_1'] = array(
			'label' => __( 'Address 1', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['number'] = array(
			'label' => __( 'Number', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['address_2'] = array(
			'label' => __( 'Address 2', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['neighborhood'] = array(
			'label' => __( 'Neighborhood', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['city'] = array(
			'label' => __( 'City', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['state'] = array(
			'label' => __( 'State', $this->plugin_slug ),
			'show'  => false
		);
		$shipping_data['country'] = array(
			'label'   => __( 'Country', $this->plugin_slug ),
			'show'    => false,
			'type'    => 'select',
			'options' => array(
				'' => __( 'Select a country&hellip;', $this->plugin_slug )
			) + $woocommerce->countries->get_allowed_countries()
		);
		$shipping_data['postcode'] = array(
			'label' => __( 'Postcode', $this->plugin_slug ),
			'show'  => false
		);

		return apply_filters( 'wcbcf_admin_shipping_fields', $shipping_data );
	}

	/**
	 * Custom billing admin fields.
	 *
	 * @since  2.9.0
	 *
	 * @param  object $order Order data.
	 *
	 * @return string        Custom information.
	 */
	public function order_data_after_billing_address( $order ) {
		global $woocommerce;

		// Get plugin settings.
		$settings = get_option( 'wcbcf_settings' );

		// Use nonce for verification.
		wp_nonce_field( basename( __FILE__ ), 'wcbcf_meta_fields' );

		$html = '<div class="wcbcf-address">';

		if ( ! $order->get_formatted_billing_address() ) {
			$html .= '<p class="none_set"><strong>' . __( 'Address', $this->plugin_slug ) . ':</strong> ' . __( 'No billing address set.', $this->plugin_slug ) . '</p>';
		} else {

			$html .= '<p><strong>' . __( 'Address', $this->plugin_slug ) . ':</strong><br />';
			if ( version_compare( $woocommerce->version, '2.0.5', '<=' ) ) {
				$html .= $order->billing_first_name . ' ' . $order->billing_last_name . '<br />';
				$html .= $order->billing_address_1 . ', ' . $order->billing_number . '<br />';
				$html .= $order->billing_address_2 . '<br />';
				$html .= $order->billing_neighborhood . '<br />';
				$html .= $order->billing_city . '<br />';
				if ( $woocommerce->countries->states[ $order->billing_country ] ) {
					$html .= $woocommerce->countries->states[ $order->billing_country ][ $order->billing_state ] . '<br />';
				} else {
					$html .= $order->billing_state . '<br />';
				}

				$html .= $order->billing_postcode . '<br />';
				$html .= $woocommerce->countries->countries[$order->billing_country] . '<br />';

			} else {
				$html .= $order->get_formatted_billing_address();
			}

			$html .= '</p>';
		}

		$html .= '<h4>' . __( 'Customer data', $this->plugin_slug ) . '</h4>';

		$html .= '<p>';

		if ( isset( $settings['person_type'] ) ) {

			// Person type information.
			if ( 1 == $order->billing_persontype ) {
				$html .= '<strong>' . __( 'CPF', $this->plugin_slug ) . ': </strong>' . $order->billing_cpf . '<br />';

				if ( isset( $settings['rg'] ) ) {
					$html .= '<strong>' . __( 'RG', $this->plugin_slug ) . ': </strong>' . $order->billing_rg . '<br />';
				}
			}

			if ( 2 == $order->billing_persontype ) {
				$html .= '<strong>' . __( 'Company Name', $this->plugin_slug ) . ': </strong>' . $order->billing_company . '<br />';
				$html .= '<strong>' . __( 'CNPJ', $this->plugin_slug ) . ': </strong>' . $order->billing_cnpj . '<br />';

				if ( isset( $settings['ie'] ) ) {
					$html .= '<strong>' . __( 'State Registration', $this->plugin_slug ) . ': </strong>' . $order->billing_ie . '<br />';
				}
			}
		} else {
			$html .= '<strong>' . __( 'Company', $this->plugin_slug ) . ': </strong>' . $order->billing_company . '<br />';
		}

		//if ( isset( $settings['birthdate_sex'] ) ) {

			// Birthdate information.
		//	$html .= '<strong>' . __( 'Birthdate', $this->plugin_slug ) . ': </strong>' . $order->billing_birthdate . '<br />';

			// Sex Information.
			//$html .= '<strong>' . __( 'Sex', $this->plugin_slug ) . ': </strong>' . $order->billing_sex . '<br />';
	//	}
		
		if ( isset( $settings['fantasia'] ) ) {
			$html .= '<strong>' . __( 'Nome Fantasia', $this->plugin_slug ) . ': </strong>' . $order->billing_fantasia . '<br />';
		}

		//$html .= '<strong>' . __( 'fantasia', $this->plugin_slug ) . ': </strong>' . $order->billing_fantasia . '<br />';

		// Cell Phone Information.
		if ( isset( $settings['cell_phone'] ) ) {
			$html .= '<strong>' . __( 'Cell Phone', $this->plugin_slug ) . ': </strong>' . $order->billing_cellphone . '<br />';
		}

		$html .= '<strong>' . __( 'Email', $this->plugin_slug ) . ': </strong>' . $order->billing_email . '<br />';

		$html .= '</p>';

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Custom billing admin fields.
	 *
	 * @since  2.9.0
	 *
	 * @param  object $order Order data.
	 *
	 * @return string        Custom information.
	 */
	public function order_data_after_shipping_address( $order ) {
		global $woocommerce;

		// Get plugin settings.
		$settings = get_option( 'wcbcf_settings' );

		$html = '<div class="wcbcf-address">';

		if ( ! $order->get_formatted_shipping_address() ) {
			$html .= '<p class="none_set"><strong>' . __( 'Address', $this->plugin_slug ) . ':</strong> ' . __( 'No shipping address set.', $this->plugin_slug ) . '</p>';
		} else {

			$html .= '<p><strong>' . __( 'Address', $this->plugin_slug ) . ':</strong><br />';
			if ( version_compare( $woocommerce->version, '2.0.5', '<=' ) ) {
				$html .= $order->billing_first_name . ' ' . $order->billing_last_name . '<br />';
				$html .= $order->billing_address_1 . ', ' . $order->billing_number . '<br />';
				$html .= $order->billing_address_2 . '<br />';
				$html .= $order->billing_neighborhood . '<br />';
				$html .= $order->billing_city . '<br />';
				if ( $woocommerce->countries->states[ $order->billing_country ] ) {
					$html .= $woocommerce->countries->states[ $order->billing_country ][ $order->billing_state ] . '<br />';
				} else {
					$html .= $order->billing_state . '<br />';
				}

				$html .= $order->billing_postcode . '<br />';
				$html .= $woocommerce->countries->countries[$order->billing_country] . '<br />';
			} else {
				$html .= $order->get_formatted_shipping_address();
			}

			$html .= '</p>';
		}

		$html .= '</div>';

		echo $html;
	}

	/**
	 * Save custom fields.
	 *
	 * @since  2.9.0
	 *
	 * @param  int  $post_id Post ID.
	 *
	 * @return mixed
	 */
	public function save_shop_data_fields( $post_id ) {
		global $post_type;

		if ( 'shop_order' != $post_type ) {
			return $post_id;
		}

		// Verify nonce.
		if ( ! isset( $_POST['wcbcf_meta_fields'] ) || ! wp_verify_nonce( $_POST['wcbcf_meta_fields'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Verify if this is an auto save routine.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Verify current user.
		if ( ! current_user_can( 'edit_pages', $post_id ) ) {
			return $post_id;
		}

		// Get plugin settings.
		$settings = get_option( 'wcbcf_settings' );

		// Update options.
		update_post_meta( $post_id, '_billing_number', woocommerce_clean( $_POST['_billing_number'] ) );
		update_post_meta( $post_id, '_billing_neighborhood', woocommerce_clean( $_POST['_billing_neighborhood'] ) );
		update_post_meta( $post_id, '_shipping_number', woocommerce_clean( $_POST['_shipping_number'] ) );
		update_post_meta( $post_id, '_shipping_neighborhood', woocommerce_clean( $_POST['_shipping_neighborhood'] ) );

		if ( isset( $settings['person_type'] ) ) {
			update_post_meta( $post_id, '_billing_persontype', woocommerce_clean( $_POST['_billing_persontype'] ) );
			update_post_meta( $post_id, '_billing_cpf', woocommerce_clean( $_POST['_billing_cpf'] ) );
			update_post_meta( $post_id, '_billing_cnpj', woocommerce_clean( $_POST['_billing_cnpj'] ) );

			if ( isset( $settings['rg'] ) ) {
				update_post_meta( $post_id, '_billing_rg', woocommerce_clean( $_POST['_billing_rg'] ) );
			}

			if ( isset( $settings['ie'] ) ) {
				update_post_meta( $post_id, '_billing_ie', woocommerce_clean( $_POST['_billing_ie'] ) );
			}
		}

		//if ( isset( $settings['birthdate_sex'] ) ) {
			//update_post_meta( $post_id, '_billing_birthdate', woocommerce_clean( $_POST['_billing_birthdate'] ) );
			//update_post_meta( $post_id, '_billing_sex', woocommerce_clean( $_POST['_billing_sex'] ) );
		//}
		
		if ( isset( $settings['fantasia'] ) ) {
				update_post_meta( $post_id, '_billing_fantasia', woocommerce_clean( $_POST['_billing_fantasia'] ) );
		}
		
		if ( isset( $settings['cell_phone'] ) ) {
			update_post_meta( $post_id, '_billing_cellphone', woocommerce_clean( $_POST['_billing_cellphone'] ) );
		}
	}

	/**
	 * Custom user edit fields.
	 *
	 * @since  2.9.0
	 *
	 * @param  array $fields Default fields.
	 *
	 * @return array         Custom fields.
	 */
	public function user_edit_fields( $fields ) {
		unset( $fields );

		// Get plugin settings.
		$settings = get_option( 'wcbcf_settings' );

		// Billing fields.
		$fields['billing']['title'] = __( 'Customer Billing Address', $this->plugin_slug );
		$fields['billing']['fields']['billing_first_name'] = array(
			'label' => __( 'First name', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_last_name'] = array(
			'label' => __( 'Last name', $this->plugin_slug ),
			'description' => ''
		);

		if ( isset( $settings['person_type'] ) ) {
			$fields['billing']['fields']['billing_cpf'] = array(
				'label' => __( 'CPF', $this->plugin_slug ),
				'description' => ''
			);

			if ( isset( $settings['rg'] ) ) {
				$fields['billing']['fields']['billing_rg'] = array(
					'label' => __( 'RG', $this->plugin_slug ),
					'description' => ''
				);
			}

			$fields['billing']['fields']['billing_company'] = array(
				'label' => __( 'Company Name', $this->plugin_slug ),
				'description' => ''
			);
			$fields['billing']['fields']['billing_cnpj'] = array(
				'label' => __( 'CNPJ', $this->plugin_slug ),
				'description' => ''
			);

			if ( isset( $settings['ie'] ) ) {
				$fields['billing']['fields']['billing_ie'] = array(
					'label' => __( 'State Registration', $this->plugin_slug ),
					'description' => ''
				);
			}
		} else {
			$fields['billing']['fields']['billing_company'] = array(
				'label' => __( 'Company', $this->plugin_slug ),
				'description' => ''
			);
		}

		//if ( isset( $settings['birthdate_sex'] ) ) {
			//$fields['billing']['fields']['billing_birthdate'] = array(
			//	'label' => __( 'Birthdate', $this->plugin_slug ),
			//	'description' => ''
		//	);
		//	$fields['billing']['fields']['billing_sex'] = array(
		//		'label' => __( 'Sex', $this->plugin_slug ),
			//	'description' => ''
		//	);
		
		 
		//}

		if ( isset( $settings['fantasia'] ) ) {
			$fields['billing']['fields']['billing_fantasia'] = array(
				'label' => __( 'Nome Fantasia', $this->plugin_slug ),
				'description' => ''
			);
		}
		$fields['billing']['fields']['billing_country'] = array(
			'label' => __( 'Country', $this->plugin_slug ),
			'description' => __( '2 letter Country code', $this->plugin_slug )
		);
		$fields['billing']['fields']['billing_postcode'] = array(
			'label' => __( 'Postcode', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_address_1'] = array(
			'label' => __( 'Address 1', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_number'] = array(
			'label' => __( 'Number', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_address_2'] = array(
			'label' => __( 'Address 2', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_neighborhood'] = array(
			'label' => __( 'Neighborhood', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_city'] = array(
			'label' => __( 'City', $this->plugin_slug ),
			'description' => ''
		);
		$fields['billing']['fields']['billing_state'] = array(
			'label' => __( 'State', $this->plugin_slug ),
			'description' => __( 'State code', $this->plugin_slug )
		);
		$fields['billing']['fields']['billing_phone'] = array(
			'label' => __( 'Telephone', $this->plugin_slug ),
			'description' => ''
		);

		if ( isset( $settings['cell_phone'] ) ) {
			$fields['billing']['fields']['billing_cellphone'] = array(
				'label' => __( 'Cell Phone', $this->plugin_slug ),
				'description' => ''
			);
		}

		$fields['billing']['fields']['billing_email'] = array(
			'label' => __( 'Email', $this->plugin_slug ),
			'description' => ''
		);

		// Shipping fields.
		$fields['shipping']['title'] = __( 'Customer Shipping Address', $this->plugin_slug );
		$fields['shipping']['fields']['shipping_first_name'] = array(
			'label' => __( 'First name', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_last_name'] = array(
			'label' => __( 'Last name', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_company'] = array(
			'label' => __( 'Company', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_country'] = array(
			'label' => __( 'Country', $this->plugin_slug ),
			'description' => __( '2 letter Country code', $this->plugin_slug )
		);
		$fields['shipping']['fields']['shipping_postcode'] = array(
			'label' => __( 'Postcode', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_address_1'] = array(
			'label' => __( 'Address 1', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_number'] = array(
			'label' => __( 'Number', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_address_2'] = array(
			'label' => __( 'Address 2', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_neighborhood'] = array(
			'label' => __( 'Neighborhood', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_city'] = array(
			'label' => __( 'City', $this->plugin_slug ),
			'description' => ''
		);
		$fields['shipping']['fields']['shipping_state'] = array(
			'label' => __( 'State', $this->plugin_slug ),
			'description' => __( 'State code', $this->plugin_slug )
		);

		$new_fields = apply_filters( 'wcbcf_customer_meta_fields', $fields );

		return $new_fields;
	}

}
