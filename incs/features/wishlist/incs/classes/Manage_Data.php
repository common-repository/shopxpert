<?php
namespace WooWishSuite;
/**
 * Manage_Data handlers class
 */
class Manage_Data {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Manage_Data]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [create]
     * @param  array  $args New argument
     * @return [int] return insert id | update
     */
    public function create( $args = [] ){
        global $wpdb;

        if ( empty( $args['product_id'] ) ) {
            return new \WP_Error( 'no-product_id', __( 'You must provide a product ID.', 'shopxpert' ) );
        }

        $defaults = [
            'user_id'     => 'NULL',
            'product_id'  => 'NULL',
            'quantity'    => 1,
            'date_added'  => current_time( 'mysql' ),
        ];
        $data = wp_parse_args( $args, $defaults );

        $get_row = $this->read_single_item( $data['user_id'], $data['product_id'] );

        if( is_object( $get_row ) && $get_row->product_id ){
            $data['quantity'] = ( $get_row->quantity + 1 );
            $this->update( $data );
        }else{
            $inserted = $wpdb->insert(
                $wpdb->prefix . 'wishsuite_list',
                $data,
                [
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                ]
            );

            if ( ! $inserted ) {
                return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'shopxpert' ) );
            }

            $this->purge_cache();

            return $wpdb->insert_id;
        }

    }

    /**
     * [read]
     * @param  array  $args
     * @return [array] product array
     */
    public function read( $args = [] ){
        global $wpdb;

        $defaults = [
            'number'  => 20,
            'user_id' => get_current_user_id(),
            'offset'  => 0,
            'orderby' => 'id',
            'order'   => 'ASC'
        ];

        $args = wp_parse_args( $args, $defaults );

        $last_changed = wp_cache_get_last_changed( 'shopxpert' );
        $key          = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
        $cache_key    = "all:$key:$last_changed";

        $sql = $args['number'] === -1 ? $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wishsuite_list
            WHERE user_id = %d
            ORDER BY %s %s
            LIMIT %d, %d",
            $args['user_id'], $args['orderby'], $args['order'], 0, 10000
        ) : $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wishsuite_list
            WHERE user_id = %d
            ORDER BY %s %s
            LIMIT %d, %d",
            $args['user_id'], $args['orderby'], $args['order'], $args['offset'], $args['number']);

        $items = wp_cache_get( $cache_key, 'shopxpert' );

        if ( false === $items ) {
            $items = $wpdb->get_results( $sql, ARRAY_A );

            wp_cache_set( $cache_key, $items, 'shopxpert' );
        }

        return $items;
    }

    /**
     * [update]
     * @param  array  $args new argument
     * @return [int]  update id
     */
    public function update( $args = [] ){
        global $wpdb;

        $defaults = [
            'product_id'  => 'NULL',
            'quantity'    => 1,
        ];
        $data = wp_parse_args( $args, $defaults );

        $user_id    = $data['user_id'];
        $product_id = $data['product_id'];
        
        unset( $data['user_id'] );
        unset( $data['product_id'] );
        unset( $data['date_added'] );

        $updated = $wpdb->update(
            $wpdb->prefix . 'wishsuite_list',
            $data,
            [ 
                'user_id'    => $user_id,
                'product_id' => $product_id
            ],
            [
                '%d',
                '%d'
            ],
            [ '%d', '%d' ]
        );

        $this->purge_cache( $user_id );

        return $updated;

    }

    /**
     * [item_count] Get the count of total product
     * @param  [int] $user_id
     * @return [int] 
     */
    public function item_count( $user_id ) {
        global $wpdb;

        $count = wp_cache_get( 'count', 'shopxpert' );

        if ( false === $count ) {
            $count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT count(id) FROM {$wpdb->prefix}wishsuite_list WHERE user_id = %d", $user_id ) );

            wp_cache_set( 'count', $count, 'shopxpert' );
        }

        return $count;
    }

    /**
     * [read_single_item] Fetch single product from DB
     * @param  [int] $user_id
     * @return [object] Table Object
     */
    public function read_single_item( $user_id, $product_id ) {
        global $wpdb;

        $product = wp_cache_get( 'wishlist-product-' . $user_id.$product_id, 'shopxpert' );

        if ( false === $product ) {
            $product = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wishsuite_list WHERE user_id = %d AND product_id = %d", $user_id, $product_id )
            );
            wp_cache_set( 'wishlist-product-' . $user_id.$product_id, $product, 'shopxpert' );
        }

        return $product;
    }

    /**
     * Delete an address
     *
     * @param  int $id
     *
     * @return int|boolean
     */
    public function delete( $user_id, $product_id ) {
        global $wpdb;

        $this->purge_cache( $user_id );

        return $wpdb->delete(
            $wpdb->prefix . 'wishsuite_list',
            [ 
                'user_id'    => $user_id,
                'product_id' => $product_id
            ],
            [ '%d', '%d' ]
        );

    }

    /**
     * [purge_cache] Manage Object Cache
     * @param  [int] $user_id
     * @return [type] 
     */
    public function purge_cache( $user_id = null ) {
        $group = 'shopxpert';

        if ( $user_id ) {
            wp_cache_delete( 'wishlist-product-' . $user_id, $group );
        }

        wp_cache_delete( 'count', $group );
        wp_cache_set( 'last_changed', microtime(), $group );

    }



}