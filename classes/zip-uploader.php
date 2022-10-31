<?php


class ZIP_Uploader {

	protected $folder = '';

	public function __construct( $folder ) {
		$this->folder = $folder;
	}

	/**
	 * Get folder name where to upload
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_name( $filename ) {
		return sanitize_title( $filename );
	}

	/**
	 * Get target path for the parent folder where all files are uploaded
	 *
	 * @return string
	 */
	public function get_target_path() {
		$upload_basePath   = ABSPATH . 'wp-content/reactpress/apps/';
		return trailingslashit( $upload_basePath ) . $this->folder;
	}

	/**
	 * Get path
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_path( $folder ) {
		return trailingslashit( $this->get_target_path() ) . $folder;
	}

	/**
	 * Check if there is an error
	 *
	 * @param $error
	 *
	 * @return bool|WP_Error
	 */
	public function check_error($error) {
		$file_errors = array(
			0 => __( "There is no error, the file uploaded with success", 'bam_courses_launcher' ),
			1 => __( "The uploaded file exceeds the upload_max_files in server settings", 'bam_courses_launcher' ),
			2 => __( "The uploaded file exceeds the MAX_FILE_SIZE from html form", 'bam_courses_launcher' ),
			3 => __( "The uploaded file uploaded only partially", 'bam_courses_launcher' ),
			4 => __( "No file was uploaded", 'bam_courses_launcher' ),
			6 => __( "Missing a temporary folder", 'bam_courses_launcher' ),
			7 => __( "Failed to write file to disk", 'bam_courses_launcher' ),
			8 => __( "A PHP extension stoped file to upload", 'bam_courses_launcher' ),
		);

		if ( $error > 0 ) {
			return new \WP_Error( 'file-error', $file_errors[ $error ] );
		}

		return true;
	}

	/**
	 * Upload File
	 *
	 * @param $file
	 *
	 * @return bool|string|true|WP_Error
	 */
	public function upload( $file ) {
		/** @var $wp_filesystem \WP_Filesystem_Direct */
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		$file_error = $file["error"];

		// Check for Errors
		if ( is_wp_error( $this->check_error( $file_error ) ) ) {
			return $this->check_error( $file_error );
		}

		$file_name       = $file["name"];
		$file_name_arr   = explode( '.', $file_name );
		$extension       = array_pop( $file_name_arr );
		$filename        = implode( '.', $file_name_arr );
		$zip_file        = sanitize_title( $filename ) . '.' . $extension;

		if ( 'zip' !== $extension ) {
			return new WP_Error( 'no-zip', 'This does not seem to be a ZIP file' );
		}

		$temp_name  = $file["tmp_name"];
		$file_size  = $file["size"];

		$current_folder = $this->get_folder_path( $this->get_folder_name( $filename ) );

		// Get default folder that contains all courses. Create if does not exists.
		$default_target = $this->get_target_path();

		if( ! file_exists( $default_target ) ){
			mkdir( $default_target );
		}

		// Get course folder path, create if not exists
		$upload_path = $this->get_folder_path( $this->get_folder_name( $filename ) );

		if ( $wp_filesystem->exists( $upload_path ) ) {
			$wp_filesystem->delete( $upload_path, true );
		}

		if ( ! $wp_filesystem->exists( $upload_path ) ) {
			$wp_filesystem->mkdir( $upload_path );
		}

		// Getting a folder where we will upload the ZIP
		$working_dir = $upload_path . '-zip';

		if ( $wp_filesystem->is_dir( $working_dir ) ) {
			$wp_filesystem->delete( $working_dir, true );
		}

		$wp_filesystem->mkdir( $working_dir );

		// Uploading ZIP file
		if( move_uploaded_file( $temp_name, $working_dir . "/" . $zip_file ) ){

			// Unzip the file to the upload path
			$unzip_result = unzip_file( $working_dir . "/" . $zip_file, $upload_path );

			if ( is_wp_error( $unzip_result ) ) {
				return $unzip_result;
			} else {
				// No errors with unzips, let's delete everything and unzip it again.
				if ( $wp_filesystem->is_dir( $upload_path ) ) {
					$wp_filesystem->delete( $upload_path, true );
				}
				$wp_filesystem->mkdir( $upload_path );
				unzip_file( $working_dir . "/" . $zip_file, $upload_path );
			}

			// Remove the uploaded zip
			@unlink( $working_dir . "/" . $zip_file );
			if ( $wp_filesystem->is_dir( $working_dir ) ) {
				$wp_filesystem->delete( $working_dir, true );
			}

			return  $upload_path;
		} else {
			return new \WP_Error( 'not-uploaded', __( 'Could not upload file', 'your_textdomain' ) );
		}
	}


}