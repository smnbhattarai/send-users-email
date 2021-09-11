<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'Dashboard', 'send-users-email' ); ?></h5>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo __( 'Total Users', 'send-users-email' ); ?></h5>
                                    <h3 class="card-text"><?php echo $users['total_users']; ?></h3>
                                </div>
                            </div>
                        </div>

						<?php foreach ( $users['avail_roles'] as $role => $total ): ?>
							<?php if ( $total > 0 ): ?>
                                <div class="col-sm-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php printf( __( '%s', 'send-users-email' ), ucfirst( $role ) ); ?></h5>
                                            <h3 class="card-text"><?php echo $total; ?></h3>
                                        </div>
                                    </div>
                                </div>
							<?php endif; ?>
						<?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo __( 'About', 'send-users-email' ); ?></h5>
                    <p class="card-text"><?php echo __( 'Send email to users by selecting individual users or bulk send emails using roles.', 'send-users-email' ); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>