( function( $ ) {
	$( document ).ready( function() {
		$( '.cpsw-dismissible-notice .notice-dismiss' ).on( 'click', function( event ) {
			const $notice = $( this ).closest( '.cpsw-dismissible-notice' );
			const noticeId = $notice.attr( 'id' );
			event.preventDefault();

			$.ajax( {
				url: cpsw_notice_ajax_object.ajax_url,
				type: 'POST',
				data: {
					action: 'dismiss_cpsw_notice',
					_security: cpsw_notice_ajax_object.notice_nonce,
					notice_id: noticeId,
				},
				success() {
					$notice.fadeOut( 'slow', function() {
						$notice.remove();
					} );
				},
				error( jqXHR, textStatus, errorThrown ) {
					// eslint-disable-next-line no-console
					console.error( 'Error dismissing notice:', textStatus, errorThrown );
				},
			} );
		} );
	} );
}( jQuery ) );
