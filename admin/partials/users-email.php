<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title mb-5 text-uppercase"><?php echo __( 'Send email to selected users', 'send-users-email' ); ?></h5>

                    <form action="javascript:void(0)" id="sue-users-email-form" method="post">

                        <div class="mb-4">
                            <label for="subject"
                                   class="form-label"><?php echo __( 'Email Subject', 'send-users-email' ); ?></label>
                            <input type="text" class="form-control subject" id="subject" name="subject" maxlength="200"
                                   placeholder="<?php echo __( 'Email subject here', 'send-users-email' ); ?>">
                        </div>

                        <div class="mb-4 sue-user-email-datatable-wrapper">
                            <div class="sue-user-email-datatable">
                                <label for="sue_users"
                                       class="form-label"><?php echo __( 'Select Users', 'send-users-email' ); ?></label>
                                <table id="sue-user-email-tbl" class="table table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="sueSelectAllUsers"></th>
                                        <th><?php echo __( 'ID', 'send-users-email' ); ?></th>
                                        <th><?php echo __( 'Name', 'send-users-email' ); ?></th>
                                        <th><?php echo __( 'Email', 'send-users-email' ); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach ( $blog_users as $user ): ?>
                                        <tr>
                                            <td><input type="checkbox" class="sueUserCheck" name="users[]"
                                                       value="<?php echo esc_html($user->ID); ?>"></td>
                                            <td><?php echo esc_html($user->ID); ?></td>
                                            <td><?php echo esc_html($user->display_name); ?></td>
                                            <td><?php echo esc_html($user->user_email); ?></td>
                                        </tr>
									<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="sue_user_email_message"
                                   class="form-label"><?php echo __( 'Email Message', 'send-users-email' ); ?></label>

							<?php
							// Initialize RTE
							wp_editor( '', 'sue_user_email_message', [ 'textarea_rows' => 12 ] );
							?>
                            <div class="message"></div>
                        </div>

                        <input type="hidden" id="_wpnonce" name="_wpnonce"
                               value="<?php echo wp_create_nonce( 'sue-email-user' ); ?>"/>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" id="sue-user-email-btn" class="btn btn-primary btn-block">
                                        <span class="dashicons dashicons-email"></span> <?php echo __( 'Send Message', 'send-users-email' ); ?>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="spinner-border text-info sue-spinner" role="status">
                                    <span class="visually-hidden"><?php echo __('Loading...', 'send-users-email'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-7 mt-2">
                                <div class="progress" style="height: 20px; display: none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'Instruction', 'send-users-email' ); ?></h5>
                    <p class="card-text"><?php echo __( 'Send email to individual users by selecting them from the user list..', 'send-users-email' ); ?></p>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'Placeholder', 'send-users-email' ); ?></h5>
                    <p class="card-text"><?php echo __( 'You can use following placeholder to replace user detail on email.', 'send-users-email' ); ?></p>
                    <table class="table table-borderless">
                        <tr>
                            <td>
                                {{user_display_name}}<br>
								<?php echo __( 'Use this placeholder to display user display name', 'send-users-email' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{user_first_name}}<br>
								<?php echo __( 'Use this placeholder to display user first name', 'send-users-email' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{user_last_name}}<br>
								<?php echo __( 'Use this placeholder to display user last name', 'send-users-email' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{user_email}}<br>
								<?php echo __( 'Use this placeholder to display user email', 'send-users-email' ); ?>
                            </td>
                        </tr>
                    </table>

                    <div class="sue-messages"></div>

                </div>
            </div>
        </div>

    </div>
</div>