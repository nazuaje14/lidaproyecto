<?php
/**
 * @since   7.5.0
 *
 * @package The7
 */

/**
 * Class The7_Aoutoloader
 */
class The7_Aoutoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	protected $include_path = '';

	/**
	 * The7_Aoutoloader constructor.
	 *
	 * @throws Exception
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = trailingslashit( PRESSCORE_DIR );
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class Class name.
	 *
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path File path.
	 *
	 * @return bool Successful or not.
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once $path;

			return true;
		}

		return false;
	}

	/**
	 * Auto-load The7 classes on demand to reduce memory consumption.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
		$class = strtolower( $class );

		if ( 0 !== strpos( $class, 'the7_' ) ) {
			return;
		}

		$file = $this->get_file_name_from_class( $class );
		$path = $this->include_path;

		if ( 0 === strpos( $class, 'the7_options' ) ) {
			$path = $this->include_path . 'extensions/options-framework/';
		} elseif ( 0 === strpos( $class, 'the7_option_field' ) ) {
			$path = $this->include_path . 'extensions/options-framework/fields/';
		} elseif ( 0 === strpos( $class, 'the7_admin' ) ) {
			$path = $this->include_path . 'admin/';
		}

		$this->load_file( $path . $file );
	}

}