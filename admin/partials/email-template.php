<?php
/**
 * @var $logo
 * @var $styles
 * @var $title
 * @var $tagline
 * @var $email_body
 * @var $footer
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php bloginfo('name'); ?></title>
    <meta charset="UTF-8"/>

    <style>
        .sue-logo td {
            text-align: center;
        }
        .sue-logo img {
            max-height: 75px;
        }
        .sue-title {
            text-align: center;
        }
        .sue-tagline {
            text-align: center;
        }
        .sue-footer td {
            text-align: center;
            padding-top: 30px;
        }
    </style>

	<?php if ( $styles ): ?>
        <style>
            <?php echo stripslashes_deep( esc_html( $styles ) ); ?>
        </style>
	<?php endif; ?>

</head>
<body>

<table class="sue-main-table">
	<?php if ( esc_url_raw( $logo ) ): ?>
        <tr class="sue-logo">
            <td>
                <img src="<?php echo esc_url_raw( $logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
            </td>
        </tr>
	<?php endif; ?>

	<?php if ( ( $title ) || $tagline ): ?>
        <tr class="sue-title-tagline">
            <td>
				<?php if ( $title ): ?>
                    <h2 class="sue-title"><?php echo stripslashes_deep( esc_html( $title ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $tagline ): ?>
                    <h5 class="sue-tagline"><?php echo stripslashes_deep( esc_html( $tagline ) ); ?></h5>
				<?php endif; ?>
            </td>
        </tr>
	<?php endif; ?>

    <tr class="sue-email-body">
        <td>
			<?php echo wp_kses_post( stripslashes_deep( $email_body ) ); ?>
        </td>
    </tr>

	<?php if ( $footer ): ?>
        <tr class="sue-footer">
            <td>
				<?php echo stripslashes_deep( esc_html( $footer ) ); ?>
            </td>
        </tr>
	<?php endif; ?>
</table>

</body>
</html>
