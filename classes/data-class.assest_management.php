<?php 

namespace ShopXpert ;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /** 
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){

        $this->init();
    }

    /**
     * [init] Init
     * @return [void]
     */
    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

        // Elementor Editor Scripts
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_elementor_editor' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

        add_filter( 'body_class', [ $this, 'body_classes' ] );

    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array] 
     */    
    public function body_classes( $classes ){

        $current_theme = wp_get_theme();
        $classes[] = 'shopxpert_current_theme_'.$current_theme->get( 'TextDomain' );

        return $classes;
    }
    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
 
        $style_list = [
           
            'shopxpert -admin' => [  

                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/css/shopxpert -admin.css',
                'version' => SHOPXPERT_VERSION
            ],
        ];
        return $style_list;

    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
            // 'slick' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/slick.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'countdown-min' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/jquery.countdown.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'shopxpert-accordion-min' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/accordion.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'select2-min' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/select2.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'wow' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/lib/js/wow.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jarallax' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/lib/js/jarallax.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'magnific-popup' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/lib/js/magnific-popup.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'one-page-nav' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/lib/js/one-page-nav.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jarallax','magnific-popup','wow','jquery' ]
            // ],
            // 'shopxpert-widgets-scripts' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/shopxpert-widgets-active.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery','slick','wc-add-to-cart-variation' ]
            // ],
            // 'shopxpert-ajax-search' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/addons/ajax-search/js/ajax-search.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'shopxpert-widgets-scripts' ]
            // ],
            // 'jquery-single-product-ajax-cart' =>[
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'shopxpert-flash-sale-Feature' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/features/flash-sale/assets/js/flash-sale.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery', 'countdown-min' ]
            // ],

            // 'shopxpert-jquery-interdependencies' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/jquery-interdependencies.min.js', 
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ],
            // ],
            'shopxpert-condition' => [
                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/shopxpert-condition.js', 
                'version' => SHOPXPERT_VERSION,
                'deps'    => [ 'jquery'],
            ],


            'shopxpert-admin-main' =>[
                'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/shopxpert-admin.js',
                'version' => SHOPXPERT_VERSION,
                'deps'    => [ 'jquery', 'wp-util', 'serializejson' ]
            ],


            // 'shopxpert-sweetalert' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/sweetalert2.min.js',
            //     'version' => SHOPXPERT_VERSION
            // ],
            // 'shopxpert-modernizr' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/modernizr.custom.63321.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jquery-selectric' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/jquery.selectric.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'jquery-ScrollMagic' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/ScrollMagic.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'babel-min' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/lib/js/babel.min.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery' ]
            // ],
            // 'shopxpert-templates' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/template_library_manager.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'jquery', 'wp-util' ]
            // ],
            // 'shopxpert-install-manager' => [
            //     'src'     => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/js/install_manager.js',
            //     'version' => SHOPXPERT_VERSION,
            //     'deps'    => [ 'wp-util', 'updates' ]
            // ],
            
        ];

        return $script_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();
    
        // Register and enqueue Scripts
        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : [];
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
            wp_enqueue_script($handle);
        } 
    
        // Register and enqueue Styles
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : [];
            wp_register_style($handle, $style['src'], $deps, $style['version']);
            wp_enqueue_style($handle);
        }
        
        // Localize Scripts
        $localizeargs = array(
            'shopxpertajaxurl' => admin_url('admin-ajax.php'),
            'ajax_nonce'       => wp_create_nonce('shopxper_nonce_action'),
        );
        wp_localize_script('shopxpert-widgets-scripts', 'shopxper_addons', $localizeargs);
    
// For Admin
if (is_admin()) {
    // Create the nonce
    $nonce = wp_create_nonce('shopxper_save_opt_nonce');

    // Localize the script with nonce and other data
    $datalocalize = array(
        'nonce' => $nonce,
        'ajaxurl' => admin_url('admin-ajax.php'),
        'message' => [
            'btntxt'  => esc_html__('Save Changes', 'shopxpert'),
            'loading' => esc_html__('Saving...', 'shopxpert'),
            'success' => esc_html__('ZZ Saved success Data', 'shopxpert'),
            'yes'     => esc_html__('Yes', 'shopxpert'),
            'cancel'  => esc_html__('Cancel', 'shopxpert'),
            'sure'    => esc_html__('Are you sure?', 'shopxpert'),
            'reseting'=> esc_html__('Resetting...', 'shopxpert'),
            'reseted' => esc_html__('Reset All Settings', 'shopxpert'),
        ],
        'option_data' => [],
    );
    wp_localize_script('shopxpert-admin-main', 'SHOPXPERT_ADMIN', $datalocalize);
    
    // Localize additional scripts as needed
    $current_user = wp_get_current_user();
    $localize_data = [
        'ajaxurl'          => admin_url('admin-ajax.php'),
        'nonce'            => $nonce,
        'adminURL'         => admin_url(),
        'elementorURL'     => admin_url('edit.php?post_type=elementor_library'),
        'version'          => SHOPXPERT_VERSION,
        'pluginURL'        => plugin_dir_url(__FILE__),
        'alldata'          => !empty(base::$template_info['templates']) ? base::$template_info['templates'] : array(),
        'prolink'          => 'https://shopxpert.com/pricing/?utm_source=admin&utm_medium=library',
        'prolabel'         => esc_html__('Pro', 'shopxpert'),
        'loadingimg'       => SHOPXPERT_ADDONS_PL_URL . 'incs/admin/assets/images/loading.gif',
        'message'          => [
            'packagedesc'=> esc_html__('in this package', 'shopxpert'),
            'allload'    => esc_html__('All Items have been Loaded', 'shopxpert'),
            'notfound'   => esc_html__('Nothing Found', 'shopxpert'),
        ],
        'buttontxt'      => [
            'tmplibrary' => esc_html__('Import to Library', 'shopxpert'),
            'tmppage'    => esc_html__('Import to Page', 'shopxpert'),
            'tmpbuilder' => esc_html__('Import to Builder', 'shopxpert'),
            'import'     => esc_html__('Import', 'shopxpert'),
            'buynow'     => esc_html__('Buy Now', 'shopxpert'),
            'preview'    => esc_html__('Preview', 'shopxpert'),
            'installing' => esc_html__('Installing..', 'shopxpert'),
            'activating' => esc_html__('Activating..', 'shopxpert'),
            'active'     => esc_html__('Active', 'shopxpert'),
        ],
        'user'           => [
            'email' => $current_user->user_email,
        ],
    ];
    wp_localize_script('shopxpert-templates', 'WLTM', $localize_data);
    wp_localize_script('shopxpert-install-manager', 'WLIM', $localize_data);
}

    }
    

    /**
     * [enqueue_frontend_scripts Load frontend scripts]
     * @return [void]
     */
    public function enqueue_frontend_scripts() {

        $current_theme = wp_get_theme( 'oceanwp' );
        // CSS File
        if ( $current_theme->exists() ){
            wp_enqueue_style( 'font-awesome-four' );
        }else{
            if( wp_style_is( 'font-awesome', 'registered' ) ){
                wp_enqueue_style( 'font-awesome' );
            }else{
                wp_enqueue_style( 'font-awesome-four' );
            }
        }
        wp_enqueue_style( 'simple-line-icons-wl' );
        wp_enqueue_style( 'htflexboxgrid' );
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'shopxpert-widgets' );
        
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'shopxpert-widgets-rtl' );
        }
    }

    /**
     * Elementor Editor Panenl Script
     *
     * @return void
     */
    public function enqueue_elementor_editor(){
        wp_enqueue_style('shopxert-elementor-editor', SHOPXPERT_ADDONS_PL_URL . 'assets/css/shopxert-elementor-editor.css',['elementor-editor'], SHOPXPERT_VERSION );
        wp_enqueue_script( 'shopxert-elementor-editor', SHOPXPERT_ADDONS_PL_URL . 'assets/js/shopxert-elementor-editor.js', ['elementor-editor', 'jquery'], SHOPXPERT_VERSION, true );

        // Localized data for elementor editor
        wp_localize_script(
            'shopxert-elementor-editor',
            'shopxertSetting',
            array(
                'hasPro'     => is_plugin_active('shopxert-addons-pro/shopxert_addons_pro.php') ? true : false,
                'proWidgets' => Widgets_Control::promotional_widget_list(),
            )
        );
    }
    

}

Assets_Management::instance();