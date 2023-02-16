<?php
/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wmue_Settings_Controller {

	public static function index() {
		$model = new Ai1wmue_Settings;

		Ai1wm_Template::render(
			'settings/index',
			array(
				'backups' => get_option( 'ai1wmue_backups', false ),
				'total'   => get_option( 'ai1wmue_total', false ),
				'days'    => get_option( 'ai1wmue_days', false ),
			),
			AI1WMUE_TEMPLATES_PATH
		);
	}

	public static function settings( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( $_POST );
		}

		// Settings update
		if ( isset( $params['ai1wmue_update'] ) ) {
			$model = new Ai1wmue_Settings;

			// Set number of backups
			if ( ! empty( $params['ai1wmue_backups'] ) ) {
				$model->set_backups( (int) $params['ai1wmue_backups'] );
			} else {
				$model->set_backups( 0 );
			}

			// Set size of backups
			if ( ! empty( $params['ai1wmue_total'] ) && ! empty( $params['ai1wmue_total_unit'] ) ) {
				$model->set_total( (int) $params['ai1wmue_total'] . trim( $params['ai1wmue_total_unit'] ) );
			} else {
				$model->set_total( 0 );
			}

			// Set number of days
			if ( ! empty( $params['ai1wmue_days'] ) ) {
				$model->set_days( (int) $params['ai1wmue_days'] );
			} else {
				$model->set_days( 0 );
			}

			// Set message
			Ai1wm_Message::flash( 'settings', __( 'Your changes have been saved.', AI1WMUE_PLUGIN_NAME ) );
		}

		// Redirect to settings page
		wp_redirect( network_admin_url( 'admin.php?page=ai1wmue_settings' ) );
		exit;
	}
}
