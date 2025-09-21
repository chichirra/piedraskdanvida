<?php
// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
namespace Hostinger\AffiliatePlugin\Api\Mercado;

use Hostinger\AffiliatePlugin\Admin\PluginSettings;
use Hostinger\AffiliatePlugin\Api\RequestsClient;
use WP_Error;
use WP_Http;
use DOMDocument;
use DOMXPath;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class Client {
    private PluginSettings $plugin_settings;
    private RequestsClient $requests_client;
    private array $headers = array();

    public function __construct( PluginSettings $plugin_settings, RequestsClient $requests_client ) {
        $this->plugin_settings = $plugin_settings;
        $this->requests_client = $requests_client;
    }

    public function get( string $endpoint, array $params ): array|WP_Error {
        $response = $this->requests_client->get( $endpoint, $params, $this->headers );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $validation = $this->validate_response( $response );

        if ( is_wp_error( $validation ) ) {
            return $validation;
        }

        return $this->extract_schema( wp_remote_retrieve_body( $response ) );
    }

    public function extract_schema( string $content ) {
        $dom = new DOMDocument();

        libxml_use_internal_errors( true );
        $dom->loadHTML( $content );
        libxml_clear_errors();

        $xpath       = new DOMXPath( $dom );
        $script_tags = $xpath->query( '//script[@type="application/ld+json"]' );

        foreach ( $script_tags as $tag ) {
            $json = trim( $tag->nodeValue );

            $decoded = json_decode( $json, true );

            if ( json_last_error() === JSON_ERROR_NONE ) {
                if ( isset( $decoded['@type'] ) && $decoded['@type'] === 'Product' ) {
                    return $decoded;
                } elseif ( isset( $decoded['@graph'] ) && is_array( $decoded['@graph'] ) ) {
                    $product_data = array();

                    foreach ( $decoded['@graph'] as $item ) {
                        if ( isset( $item['@type'] ) && $item['@type'] === 'Product' ) {
                            $product_data[] = $item;
                        }
                    }

                    return $product_data;
                }
            }
        }

        return array();
    }

    private function validate_response( array $response ): WP_Error|bool {
        $response_code = wp_remote_retrieve_response_code( $response );

        if ( empty( $response_code ) || $response_code !== 200 ) {
            return new WP_Error(
                'data_invalid',
                __( 'Sorry, there was a problem with request.', 'hostinger-affiliate-plugin' ),
                array(
                    'status' => $response_code,
                    'errors' => '',
                )
            );
        }

        return true;
    }
}
