<?php
namespace Hostinger\AffiliatePlugin\Api\Mercado\Api;

use Hostinger\AffiliatePlugin\Api\Amazon\AmazonApi\Request\GetProductDataRequest;
use Hostinger\AffiliatePlugin\Api\Mercado\Client;
use Hostinger\AffiliatePlugin\Repositories\ProductRepository;
use Hostinger\AffiliatePlugin\Admin\PluginSettings;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class ProductApi {
    private const GET_ITEM_BASE_PATH = '/-/p/';
    private Client $client;
    private ProductRepository $product_repository;
    private PluginSettings $plugin_settings;

    public function __construct( Client $client, ProductRepository $product_repository, PluginSettings $plugin_settings ) {
        $this->client             = $client;
        $this->product_repository = $product_repository;
        $this->plugin_settings    = $plugin_settings;
    }

    public function product_data( GetProductDataRequest $request ): array|WP_Error {
        $products = array();
        $domain   = $this->plugin_settings->get_plugin_settings()->mercado->get_locale_domain();
        $endpoint = 'https://' . $domain . self::GET_ITEM_BASE_PATH;

        foreach ( $request->get_item_ids() as $item_id ) {
            $request = $this->client->get( $endpoint . $item_id, array() );

            if ( ! is_wp_error( $request ) ) {
                $products[] = $request;
            }
        }

        return $products;
    }
}
