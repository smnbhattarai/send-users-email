<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-5 text-uppercase"><?php echo __( 'Settings', 'send-users-email' ); ?></h5>

                    <div class="sue-messages"></div>

                    <form action="javascript:void(0)" id="sue-settings-form" method="post">

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <td style="width: 25%">
                                        <label for="logo" class="form-label">Logo URL</label>
                                        <div id="logoHelp"
                                             class="form-text"><?php echo __( 'Add email header logo URL here. If left blank, logo will not be used.', 'send-users-email' ) ?></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="logo" name="logo" value="<?php echo $logo; ?>"
                                               placeholder="<?php bloginfo( 'url' ); ?>/wp-content/uploads/logo.png"
                                               aria-describedby="logoHelp">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="title" class="form-label">Email Title</label>
                                        <div id="titleHelp"
                                             class="form-text"><?php echo __( 'This value will be shown below logo image.', 'send-users-email' ) ?></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>"
                                               placeholder="<?php bloginfo( 'name' ); ?>"
                                               aria-describedby="titleHelp">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="tagline" class="form-label">Email Tagline</label>
                                        <div id="taglineHelp"
                                             class="form-text"><?php echo __( 'This value will be shown below email title image.', 'send-users-email' ) ?></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="tagline" name="tagline" value="<?php echo $tagline; ?>"
                                               placeholder="<?php bloginfo( 'description' ); ?>"
                                               aria-describedby="taglineHelp">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="footer" class="form-label">Email Footer</label>
                                        <div id="footerHelp"
                                             class="form-text"><?php echo __( 'Email footer content will be added to all emails at footer part of email.', 'send-users-email' ) ?></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="footer" name="footer" value="<?php echo $footer; ?>"
                                               placeholder="Email footer content"
                                               aria-describedby="footerHelp">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="spinner-border text-info sue-spinner" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <input type="hidden" id="_wpnonce" name="_wpnonce"
                                               value="<?php echo wp_create_nonce( 'sue-email-user' ); ?>"/></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary" id="sue-settings-btn">
                                            <span class="dashicons dashicons-admin-settings"></span> <?php echo __( 'Save Settings', 'send-users-email' ); ?>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'Instruction', 'send-users-email' ); ?></h5>
                    <p class="card-text"><?php echo __( 'Please configure email settings before sending emails.', 'send-users-email' ); ?></p>
                    <p class="card-text"><?php echo __( 'If the settings fields are left blank, the corresponding section will not be added to email.', 'send-users-email' ); ?></p>
                    <p class="card-text"><?php echo __( 'Example: If you leave logo setting blank, no logo will be added to outgoing emails.', 'send-users-email' ); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>