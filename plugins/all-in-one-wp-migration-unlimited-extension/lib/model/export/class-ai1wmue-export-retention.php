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

class Ai1wmue_Export_Retention {
	public static function execute( $params ) {
		$backups = Ai1wm_Backups::get_files();
		if ( count( $backups ) === 0 ) {
			return $params; // No backups, no need to apply backup retention
		}

		// The order is very important - we delete files by date, by size, and finally by total count
		self::delete_backups_older_than();
		self::delete_backups_when_total_size_over();
		self::delete_backups_when_total_count_over();

		return $params;
	}

	private static function delete_backups_older_than() {
		$backups = Ai1wm_Backups::get_files();
		$days    = intval( get_option( 'ai1wmue_days', 0 ) );
		if ( $days > 0 ) {
			foreach ( $backups as $backup ) {
				if ( $backup['mtime'] <= time() - $days * 86400 ) {
					Ai1wm_Backups::delete_file( $backup['filename'] );
				}
			}
		}
	}

	private static function delete_backups_when_total_size_over() {
		$backups        = Ai1wm_Backups::get_files();
		$retention_size = ai1wm_parse_size( get_option( 'ai1wmue_total', 0 ) );

		// Get the size of the latest backup before we remove it
		$size_of_backups = $backups[0]['size'];

		// Remove the latest backup, the user should have at least one backup
		array_shift( $backups );

		if ( $retention_size > 0 ) {
			foreach ( $backups as $backup ) {
				if ( $size_of_backups + $backup['size'] > $retention_size ) {
					Ai1wm_Backups::delete_file( $backup['filename'] );
				} else {
					$size_of_backups += $backup['size'];
				}
			}
		}
	}

	private static function delete_backups_when_total_count_over() {
		$backups = Ai1wm_Backups::get_files();
		$limit   = intval( get_option( 'ai1wmue_backups', 0 ) );

		if ( $limit > 0 ) {
			if ( count( $backups ) > $limit ) {
				for ( $i = $limit; $i < count( $backups ); $i++ ) {
					Ai1wm_Backups::delete_file( $backups[ $i ]['filename'] );
				}
			}
		}
	}
}
