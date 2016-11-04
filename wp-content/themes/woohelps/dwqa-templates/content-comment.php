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
		<?php $user = get_user_by( 'id', $comment->user_id ); ?>

		<dl class="dwqa-comment-meta-info">
			<dt>
				<a href="<?php echo dwqa_get_author_link( $comment->user_id ); ?>">
					<?php echo get_avatar( $comment->user_id, 16 ) ?>
				</a>
			</dt>
			<dd>
				<label>
					<a href="<?php echo dwqa_get_author_link( $comment->user_id ); ?>">
						<?php
							$user_id = $comment->user_id;
							$xProfileArr = getXprofile($user_id);
							$xWord = isset($xProfileArr['一句话描述']) ? $xProfileArr['一句话描述'] : '';
							$comment_display_name = dwqa_get_author() . ' ' . $xWord;
						?>
					<span class="best-answer-author"><?=$comment_display_name ?></span></a>
					<div class="dwqa-questions-desc"><?php echo isset($xProfileArr['微信显示名']) ? ' 微信显示名: '.$xProfileArr['微信显示名'] : '' ?></div>
					<div class="dwqa-questions-desc"><?php echo isset($xProfileArr['手机']) ? '手机:'.$xProfileArr['手机'] : '' ?></div>
					</a>
				</label>
				<?php comment_text(); ?>
				<span class="dwqa-time-style"><?php echo get_comment_time( 'Y-m-d' ) ?></span>
			</dd>
		</dl>

		<div class="dwqa-comment-actions">
			<?php if ( dwqa_current_user_can( 'edit_comment' ) ) : ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'comment_edit' => $comment->comment_ID ) ) ) ?>"><?php _e( 'Edit', 'dwqa' ) ?></a>
			<?php endif; ?>
			<?php if ( dwqa_current_user_can( 'delete_comment' ) ) : ?>
				<a class="dwqa-delete-comment" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'dwqa-action-delete-comment', 'comment_id' => $comment->comment_ID ), admin_url( 'admin-ajax.php' ) ), '_dwqa_delete_comment' ) ?>"><?php _e( 'Delete', 'dwqa' ) ?></a>
			<?php endif; ?>
		</div>
	</div>

</div>
