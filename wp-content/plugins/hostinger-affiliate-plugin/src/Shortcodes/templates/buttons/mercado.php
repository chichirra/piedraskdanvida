<a href="<?php echo $this->render_product_url( $product ); ?>" class="<?php echo ! empty( $button_class ) ? $button_class : ''; ?>" target="_blank" rel="nofollow noopener noreferrer">
    <?php echo $this->shortcode_manager->render_buy_now_button_label( $product ); ?>
</a>
