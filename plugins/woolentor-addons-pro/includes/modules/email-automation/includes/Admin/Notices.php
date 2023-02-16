<?php
/**
 * Notices.
 */

namespace WLEA\Admin;

/**
 * Notices class.
 */
class Notices {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_notices', array( $this, 'init_notices' ) );
    }

    /**
     * Initialize notices.
     */
    public function init_notices() {
        $this->tasks_notices();
        $this->logs_notices();
        $this->duplicator_notices();
    }

    /**
     * Tasks notices.
     */
    protected function tasks_notices() {
        if ( isset( $_GET['page'] ) && 'wlea-tasks' === sanitize_key( $_GET['page'] ) ) {
            $tasks_activated = isset( $_GET['wlea-activated'] ) ? absint( $_GET['wlea-activated'] ) : 0;
            $tasks_deactivated = isset( $_GET['wlea-deactivated'] ) ? absint( $_GET['wlea-deactivated'] ) : 0;
            $tasks_deleted = isset( $_GET['wlea-deleted'] ) ? absint( $_GET['wlea-deleted'] ) : 0;

            if ( 0 < $tasks_activated ) {
                $class = 'notice notice-success';

                if ( 1 < $tasks_activated ) {
                    $message = sprintf( esc_html__( '%1$s tasks has been activated.', 'woolentor-pro' ), $tasks_activated );
                } else {
                    $message = sprintf( esc_html__( '%1$s task has been activated.', 'woolentor-pro' ), $tasks_activated );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            if ( 0 < $tasks_deactivated ) {
                $class = 'notice notice-success';

                if ( 1 < $tasks_deactivated ) {
                    $message = sprintf( esc_html__( '%1$s tasks has been deactivated.', 'woolentor-pro' ), $tasks_deactivated );
                } else {
                    $message = sprintf( esc_html__( '%1$s task has been deactivated.', 'woolentor-pro' ), $tasks_deactivated );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            if ( 0 < $tasks_deleted ) {
                $class = 'notice notice-success';

                if ( 1 < $tasks_deleted ) {
                    $message = sprintf( esc_html__( '%1$s tasks has been deleted.', 'woolentor-pro' ), $tasks_deleted );
                } else {
                    $message = sprintf( esc_html__( '%1$s task has been deleted.', 'woolentor-pro' ), $tasks_deleted );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            do_action( 'wlea_add_tasks_notices' );
        }
    }

    /**
     * Logs notices.
     */
    protected function logs_notices() {
        if ( isset( $_GET['page'] ) && 'wlea-logs' === sanitize_key( $_GET['page'] ) ) {
            $logs_marked_success = isset( $_GET['wlea-marked-success'] ) ? absint( $_GET['wlea-marked-success'] ) : 0;
            $logs_marked_failed = isset( $_GET['wlea-marked-failed'] ) ? absint( $_GET['wlea-marked-failed'] ) : 0;
            $logs_deleted = isset( $_GET['wlea-deleted'] ) ? absint( $_GET['wlea-deleted'] ) : 0;

            if ( 0 < $logs_marked_success ) {
                $class = 'notice notice-success';

                if ( 1 < $logs_marked_success ) {
                    $message = sprintf( esc_html__( '%1$s logs has been marked success.', 'woolentor-pro' ), $logs_marked_success );
                } else {
                    $message = sprintf( esc_html__( '%1$s log has been marked success.', 'woolentor-pro' ), $logs_marked_success );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            if ( 0 < $logs_marked_failed ) {
                $class = 'notice notice-success';

                if ( 1 < $logs_marked_failed ) {
                    $message = sprintf( esc_html__( '%1$s logs has been marked failed.', 'woolentor-pro' ), $logs_marked_failed );
                } else {
                    $message = sprintf( esc_html__( '%1$s log has been marked failed.', 'woolentor-pro' ), $logs_marked_failed );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            if ( 0 < $logs_deleted ) {
                $class = 'notice notice-success';

                if ( 1 < $logs_deleted ) {
                    $message = sprintf( esc_html__( '%1$s logs has been deleted.', 'woolentor-pro' ), $logs_deleted );
                } else {
                    $message = sprintf( esc_html__( '%1$s log has been deleted.', 'woolentor-pro' ), $logs_deleted );
                }

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
            }

            do_action( 'wlea_add_logs_notices' );
        }
    }

    /**
     * Duplicator notices.
     */
    protected function duplicator_notices() {
        $post_id = ( isset( $_POST['wlea-duplicate-post-id'] ) ? wlea_cast( $_POST['wlea-duplicate-post-id'], 'absint' ) : 0 );

		if ( empty( $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		if ( ( 'wlea-email' !== $post_type ) && ( 'wlea-workflow' !== $post_type ) ) {
			return;
		}

		$message = '';

		$screen = get_current_screen();
		$base = isset( $screen->base ) ? $screen->base : '';
		$post_type = isset( $screen->post_type ) ? $screen->post_type : '';

		if ( 'wlea-email' === $post_type && 'edit' === $base ) {
			$message = sprintf( esc_html__( 'Successfully Duplicated. You can edit your new Email %1$shere%2$s.', 'woolentor-pro' ), '<a href="' . esc_url( get_edit_post_link( $post_id ) ) . '">', '</a>' );
		} elseif ( 'wlea-workflow' === $post_type && 'edit' === $base ) {
			$message = sprintf( esc_html__( 'Successfully Duplicated. You can edit your new Workflow %1$shere%2$s.', 'woolentor-pro' ), '<a href="' . esc_url( get_edit_post_link( $post_id ) ) . '">', '</a>' );
		}

		if ( ! empty( $message ) ) {
			printf( '<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
		}
    }

}