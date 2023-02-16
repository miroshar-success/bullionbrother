<?php
/**
 * Menus.
 */

namespace WLEA\Admin;

/**
 * Class.
 */
class Menus {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 225 );
        add_filter( 'parent_file', array( $this, 'parent_admin_menu' ) );

        add_filter( 'views_edit-wlea-email', array( $this, 'email_tabs' ) );
        add_filter( 'views_edit-wlea-workflow', array( $this, 'workflow_tabs' ) );
    }

    /**
     * Admin menu.
     */
    public function admin_menu() {
        $menu_label = esc_html__( 'Email Automation', 'woolentor-pro' );

        $page = ( isset( $_GET['page'] ) ? wlea_cast( $_GET['page'], 'key' ) : '' );
        $post_type = ( isset( $_GET['post_type'] ) ? wlea_cast( $_GET['post_type'], 'key' ) : '' );

        if ( empty( $post_type ) ) {
            if ( 'wlea-tasks' === $page ) {
                add_submenu_page( 'woolentor_page', $menu_label, $menu_label, 'manage_options', 'wlea-tasks', array( $this, 'email_tasks' ) );
            } elseif ( 'wlea-logs' === $page ) {
                add_submenu_page( 'woolentor_page', $menu_label, $menu_label, 'manage_options', 'wlea-logs', array( $this, 'email_logs' ) );
            } else {
                add_submenu_page( 'woolentor_page', $menu_label, $menu_label, 'manage_options', 'edit.php?post_type=wlea-email', null );
            }
        } else {
            if ( 'wlea-workflow' === $post_type ) {
                add_submenu_page( 'woolentor_page', $menu_label, $menu_label, 'manage_options', 'edit.php?post_type=wlea-workflow', null );
            } else {
                add_submenu_page( 'woolentor_page', $menu_label, $menu_label, 'manage_options', 'edit.php?post_type=wlea-email', null );
            }
        }
    }

    /**
     * Email tasks.
     */
    public function email_tasks() {
        $this->fix_request_uri();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Tasks', 'woolentor-pro' ); ?></h1>
            <hr class="wp-header-end">
            <?php $this->tasks_tabs(); ?>
            <form id="posts-filter" method="get">
                <input type="hidden" name="page" value="wlea-tasks" />
                <?php \WLEA\Admin\Table::tasks(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Email logs.
     */
    public function email_logs() {
        $this->fix_request_uri();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Logs', 'woolentor-pro' ); ?></h1>
            <hr class="wp-header-end">
            <?php $this->logs_tabs(); ?>
            <form id="posts-filter" method="get">
                <input type="hidden" name="page" value="wlea-logs" />
                <?php \WLEA\Admin\Table::logs(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Parent admin menu.
     */
    public function parent_admin_menu( $parent_menu ) {
        $page = ( isset( $_GET['page'] ) ? wlea_cast( $_GET['page'], 'key' ) : '' );

        $post_type = get_post_type();
        $post_type = ( ! empty( $post_type ) ? $post_type : ( isset( $_GET['post_type'] ) ? wlea_cast( $_GET['post_type'], 'key' ) : '' ) );

        if ( empty( $post_type ) ) {
            if ( 'wlea-tasks' === $page ) {
                $parent_menu = 'woolentor_page';
            } elseif ( 'wlea-logs' === $page ) {
                $parent_menu = 'woolentor_page';
            }
        } else {
            if ( 'wlea-email' === $post_type ) {
                $parent_menu = 'woolentor_page';
            } elseif ( 'wlea-workflow' === $post_type ) {
                $parent_menu = 'woolentor_page';
            }
        }

        return $parent_menu;
    }

    /**
     * Print email tabs.
     */
    public function email_tabs( $views ) {
        ?>
        <div class="wlea-admin-page-tab">
            <div class="wlea-tabs wlea-clearfix">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-email' ) ); ?>" class="wlea-tab wlea-active-tab"><?php esc_html_e( 'Email Templates', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-workflow' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Workflows', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-tasks' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Scheduled Tasks', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-logs' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Performed Tasks', 'woolentor-pro' ); ?></a>
            </div>
        </div>
        <?php

        return $views;
    }

    /**
     * Print workflow tabs.
     */
    public function workflow_tabs( $views ) {
        ?>
        <div class="wlea-admin-page-tab">
            <div class="wlea-tabs wlea-clearfix">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-email' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Email Templates', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-workflow' ) ); ?>" class="wlea-tab wlea-active-tab"><?php esc_html_e( 'Workflows', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-tasks' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Scheduled Tasks', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-logs' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Performed Tasks', 'woolentor-pro' ); ?></a>
            </div>
        </div>
        <?php

        return $views;
    }

    /**
     * Tasks tabs.
     */
    public function tasks_tabs() {
        ?>
        <div class="wlea-admin-page-tab">
            <div class="wlea-tabs wlea-clearfix">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-email' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Email Templates', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-workflow' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Workflows', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-tasks' ) ); ?>" class="wlea-tab wlea-active-tab"><?php esc_html_e( 'Scheduled Tasks', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-logs' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Performed Tasks', 'woolentor-pro' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Logs tabs.
     */
    public function logs_tabs() {
        ?>
        <div class="wlea-admin-page-tab">
            <div class="wlea-tabs wlea-clearfix">
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-email' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Email Templates', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wlea-workflow' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Workflows', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-tasks' ) ); ?>" class="wlea-tab"><?php esc_html_e( 'Scheduled Tasks', 'woolentor-pro' ); ?></a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wlea-logs' ) ); ?>" class="wlea-tab wlea-active-tab"><?php esc_html_e( 'Performed Tasks', 'woolentor-pro' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Fix request URI.
     */
    protected function fix_request_uri() {
        $redirect_args_to_remove = array();

        if ( isset( $_REQUEST['_wp_http_referer'] ) ) {
            $redirect_args_to_remove[] = '_wp_http_referer';
        }

        if ( isset( $_REQUEST['s'] ) && ( 1 > strlen( $_REQUEST['s'] ) ) ) {
            $redirect_args_to_remove[] = 's';
        }

        if ( isset( $_REQUEST['event'] ) && ( 1 > strlen( $_REQUEST['event'] ) ) ) {
            $redirect_args_to_remove[] = 'event';
        }

        if ( isset( $_REQUEST['status'] ) && ( 1 > strlen( $_REQUEST['status'] ) ) ) {
            $redirect_args_to_remove[] = 'status';
        }

        if ( ! empty( $redirect_args_to_remove ) ) {
            wp_redirect( remove_query_arg( $redirect_args_to_remove, wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
            exit();
        }
    }

}