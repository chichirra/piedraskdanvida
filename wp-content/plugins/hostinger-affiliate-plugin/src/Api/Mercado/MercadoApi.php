<?php
namespace Hostinger\AffiliatePlugin\Api\Mercado;

use Hostinger\AffiliatePlugin\Api\Mercado\Api\ProductApi;
use Hostinger\AffiliatePlugin\Api\Mercado\Api\SearchApi;
use Hostinger\AffiliatePlugin\Repositories\ProductRepository;
use Hostinger\AffiliatePlugin\Admin\PluginSettings;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class MercadoApi {
    private Client $client;
    private ProductRepository $product_repository;
    private PluginSettings $plugin_settings;

    public function __construct( Client $client, ProductRepository $product_repository, PluginSettings $plugin_settings ) {
        $this->client             = $client;
        $this->product_repository = $product_repository;
        $this->plugin_settings    = $plugin_settings;
    }

    public function search_api(): SearchApi {
        return new SearchApi( $this->client, $this->plugin_settings );
    }

    public function product_api(): ProductApi {
        return new ProductApi( $this->client, $this->product_repository, $this->plugin_settings );
    }
}
