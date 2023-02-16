<?php
/**
 * Copyright (C) 2014-2022 ServMask Inc.
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

class Ai1wmue_Stats_Controller {

	public static function export( $params ) {
		if ( isset( $params['ai1wm_manual_export'] ) ) {
			self::send( 'export' );
		}
	}

	public static function import( $params ) {
		if ( isset( $params['ai1wm_manual_restore'] ) ) {
			self::send( 'restore' );
		}

		if ( isset( $params['ai1wm_manual_import'] ) ) {
			self::send( 'import' );
		}
	}

	protected static function send( $action ) {
		if ( constant( 'AI1WMUE_PURCHASE_ID' ) ) {
			global $wpdb;

			$url = implode(
				'/',
				array(
					AI1WMUE_STATS_URL,
					AI1WMUE_PURCHASE_ID,
					$action,
				)
			);

			wp_remote_post(
				$url,
				array(
					'timeout' => 5,
					'body'    => array(
						'url'           => get_site_url(),
						'email'         => get_option( 'admin_email' ),
						'wp_version'    => get_bloginfo( 'version' ),
						'php_version'   => PHP_VERSION,
						'mysql_version' => $wpdb->db_version(),
					),
				)
			);
		}
	}
}
