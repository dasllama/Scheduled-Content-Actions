/**
 * Feature Name:	Scripts
 * Author:			HerrLlama for Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

( function( $ ) {
    var ScheduledContentActionsScripts = {
        init: function() {
        	
        	// bind add action
        	ScheduledContentActionsScripts.bindAddAction();
        	// bind delete action
        	ScheduledContentActionsScripts.bindDeleteAction();
        },
        
        bindAddAction: function() {
        	$( document ).on( 'click', '#sca-newaction-submit', function() {
        		
        		// check type
        		var type = $( this ).parent( '.sca-new-action-container' ).find( '#sca-type' ).val();
        		if ( type == '' ) {
        			$( '.sca-new-action-container' ).find( '.sca-type-container' ).addClass( 'error' );
        			return false;
        		}
        		
        		// remove errors
        		$( '.sca-new-action-container' ).find( '.sca-type-container' ).removeClass( 'error' );
        		
        		var postData = {
        			action: 'sca_add_action',
        			type: type,
        			label: $( this ).parent( '.sca-new-action-container' ).find( '#sca-type option:selected' ).text(),
        			postId: $( this ).parent( '.sca-new-action-container' ).find( '#sca-post-id' ).val(),
        			dateDay: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-day' ).val(),
        			dateMonth: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-month' ).val(),
        			dateYear: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-year' ).val(),
        			dateHour: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-hour' ).val(),
        			dateMin: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-min' ).val(),
        			dateSec: $( this ).parent( '.sca-new-action-container' ).find( '#sca-date-sec' ).val()
        		};
        		
        		$.post( ajaxurl, postData, function( response ) {
        			
        			var jresponse = $.parseJSON( response );
        			
        			if ( jresponse.error == '1' ) {
        				$( '.sca-new-action-container' ).prepend( '<div class="error"><p>' + jresponse.msg + '</p></div>' );
        			} else {
        				if ( $( '.sca-new-action-container' ).find( 'div.error' ).length )
        					$( '.sca-new-action-container' ).find( 'div.error' ).remove();
        				
        				if ( $( '#sca' ).length ) {
        					if ( $( '#sca' ).find( '.td-' + jresponse.action_time ).length ) {
        						$( '#sca' ).find( '.td-' + jresponse.action_time ).append( '<div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div>' );
        					} else {
        						$( '#sca' ).find( 'tbody' ).append( '<tr><td class="left">' + jresponse.action_date + '</td><td class="td-' + jresponse.action_time + '"><div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div></td></tr>' );
        					}
        				} else {
        					$( '.sca-current-action-container' ).html( '<table id="sca"><thead><tr><th class="left">' + jresponse.ln_date + '</th><th>' + jresponse.ln_action  + '</th></tr></thead><tbody><tr><td class="left">' + jresponse.action_date + '</td><td class="td-' + jresponse.action_time + '"><div class="sca-action"><a href="#" class="remove-action" data-postid="' + postData.postId + '" data-time="' + jresponse.action_time + '" data-action="' + postData.type + '">&nbsp;</a>&nbsp;' + postData.label + '</div></td></tr></tbody></table>' );
        				}
        			}
        		} );
        		
        		// prevent clicking reload
				return false;
        	} );
        },
        
        bindDeleteAction: function() {
        	$( document ).on( 'click', '.remove-action', function() {
        		var ele = $( this );
        		var postData = {
        			action: 'sca_delete_action',
        			type: $( this ).data( 'action' ),
        			postId: $( this ).data( 'postid' ),
        			time: $( this ).data( 'time' )
        		};
    			$.post( ajaxurl, postData, function( response ) {
        			ele.parent().css( 'background', '#f00' );
        			ele.parent().delay( 500 ).slideUp( 'normal', function() {
        				var td = $( this ).parent();
        				$( this ).remove(); 
        				if ( td.find( '.sca-action' ).length == 0 ) {
        					var tr = td.parent( 'tr' )
        					tr.remove();
        					if ( tr.parent( 'tbody' ).children( 'tr' ).length == 0 ) {
        						$( '.sca-current-action-container' ).html( '' );
        					}
        				}
        			} );
        		} );
        		return false;
        	} );
        },
    };
    $( document ).ready( function( $ ) {
    	ScheduledContentActionsScripts.init();
    } );
} )( jQuery );