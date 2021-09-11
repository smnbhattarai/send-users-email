<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-5 text-uppercase"><?php echo __( 'Send email to selected users', 'send-users-email' ); ?></h5>

                    <form id="sue-users-email-form">

                        <div class="mb-5">
                            <label for="subject"
                                   class="form-label"><?php echo __( 'Email Subject', 'send-users-email' ); ?></label>
                            <input type="email" class="form-control" id="subject" name="subject"
                                   placeholder="<?php echo __( 'Email subject here', 'send-users-email' ); ?>">
                        </div>

                        <div class="mb-5">
                            <label for="sue-users" class="form-label"><?php echo __( 'Select Users', 'send-users-email' ); ?></label>
                            <select class="form-select" name="users" id="sue-users" multiple>
                                <?php foreach($blog_users as $user): ?>
                                <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?> (<?php echo $user->user_email; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="message"
                                   class="form-label"><?php echo __( 'Email Message', 'send-users-email' ); ?></label>
                            <textarea class="form-control" id="message" rows="8" name="message"
                                      placeholder="<?php echo __( 'Email message here', 'send-users-email' ); ?>"></textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-lg"><?php echo __( 'Send Message', 'send-users-email' ); ?></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'Instruction', 'send-users-email' ); ?></h5>
                    <p class="card-text"><?php echo __( 'Send email to individual users by selecting them from the user list..', 'send-users-email' ); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>