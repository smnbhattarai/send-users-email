<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Send Users Email</title>
</head>
<body style="background-color: #ffffff; color: #000;">

<table style="width: 50%; margin: 0 auto;">
	<?php if ( $logo ): ?>
        <tr>
            <td style="text-align: center; padding: 15px 0;">
                <img src="<?php echo $logo; ?>" height="75"/>
            </td>
        </tr>
	<?php endif; ?>

	<?php if ( $title || $tagline ): ?>
        <tr>
            <td style="text-align: center; padding: 15px 0;">
				<?php if ( $title ): ?>
                    <h2 style="margin-bottom: 5px;"><?php echo $title; ?></h2>
				<?php endif; ?>

				<?php if ( $tagline ): ?>
                    <h5 style="margin-top: 5px;"><?php echo $tagline; ?></h5>
				<?php endif; ?>
            </td>
        </tr>
	<?php endif; ?>

    <tr>
        <td style="padding: 15px 0;">
			<?php echo stripslashes_deep( $email_body ); ?>
        </td>
    </tr>

	<?php if ( $footer ): ?>
        <tr>
            <td style="text-align: center; padding: 15px 0; font-size: 0.9em;">
				<?php echo $footer; ?>
            </td>
        </tr>
	<?php endif; ?>
</table>

</body>
</html>
