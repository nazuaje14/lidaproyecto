<?php
/**
 * Main template for the dev page.
 *
 * @package The7/Dev/Templates
 */

defined( 'ABSPATH' ) || exit;

$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'tools';
?>
<nav class="nav-tab-wrapper">
	<?php
	$tabs = array(
		'tools'   => 'Tools',
		'beta'    => 'Beta',
		'install' => 'Installation',
	);
	foreach ( $tabs as $tab_id => $tab_title ) {
		$act_class = $tab_id === $tab ? 'nav-tab-active' : '';
		?>
		<a href="<?php echo admin_url( 'admin.php?page=the7-dev&tab=' . $tab_id ); ?>" class="nav-tab <?php echo $act_class; ?>"><?php echo $tab_title; ?></a>
		<?php
	}
	?>
</nav>
<div id="the7-dashboard" class="wrap">
<?php get_template_part( 'inc/mods/dev-mode/views/tab', $tab ); ?>
</div>
