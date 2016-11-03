<?php
/**
 * The template for displaying content comment
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.4.2
 */
?>
<?php global $comment; ?>
<div class="dwqa-comment">
	<div class="dwqa-comment-meta">
		<div>
			<?php $user = get_user_by( 'id', $comment->user_id ); ?>
			<a href="<?php echo dwqa_get_author_link( $comment->user_id ); ?>"><?php echo get_avatar( $comment->user_id, 16 ) ?><?php echo get_comment_author() ?></a>
		</div>
		<div class="dwqa-comment-actions">
			<?php if ( dwqa_current_user_can( 'edit_comment' ) ) : ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'comment_edit' => $comment->comment_ID ) ) ) ?>"><?php _e( 'Edit', 'dwqa' ) ?></a>
			<?php endif; ?>
			<?php if ( dwqa_current_user_can( 'delete_comment' ) ) : ?>
				<a class="dwqa-delete-comment" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'dwqa-action-delete-comment', 'comment_id' => $comment->comment_ID ), admin_url( 'admin-ajax.php' ) ), '_dwqa_delete_comment' ) ?>"><?php _e( 'Delete', 'dwqa' ) ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php comment_text(); ?>
	<div class="dwqa-time-style">
		<?php
			$d = "Y-m-d";
			$comment_date = get_comment_date( $d, $comment_ID );
			echo "发布于".$comment_date;
		?>
	</div>
</div>
