<?php

/**
 * Plugin Name: Contact Form
 * Description: Ajoute un formulaire de contact à votre site WordPress.
 * Author: MOUAD JEBBARI
 * Version: 1.0
 */

function contact_form_add_menu_item()
{
    add_menu_page(
        'Contact Form',
        'Contact Form',
        'manage_options',
        'contact-form',
        'contact_form_display_page'
    );
}

add_action('admin_menu', 'contact_form_add_menu_item');


function contact_form_display_page()
{
 global $wpdb;
 
 $wp_contact_form = $wpdb->prefix . 'contact_form';
 
  $results = $wpdb->get_results( "SELECT * FROM $wp_contact_form" );

  echo '<h1>Contact form</h1>';
  echo '<table class="mytable">';
  echo ' <thead>';
  echo "<tr><th>Nom</th><th>Prenom</th><th>Email</th><th>Sujet</th><th>Message</th><th>Date d'envoie</th></tr>";
  echo " </thead>";
  echo " <tbody>";
  foreach ( $results as $row ) {
    echo '<tr>';
    echo '<td>' . $row->nom . '</td>';
    echo '<td>' . $row->prenom . '</td>';
    echo '<td>' . $row->email . '</td>';
    echo '<td>' . $row->sujet . '</td>';
    echo '<td>' . $row->message . '</td>';
    echo '<td>' . $row->date_envoi . '</td>';
    echo '</tr>';
  }
  echo " </tbody>";
  echo '</table>';
}


function contact_form_create_table()
{
    global $wpdb;
    $wp_contact_form = $wpdb->prefix . 'contact_form';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $wp_contact_form (
            id INT(9) NOT NULL AUTO_INCREMENT,
            sujet VARCHAR(200) NOT NULL,
            nom VARCHAR(200) NOT NULL,
            prenom VARCHAR(200) NOT NULL,
            email VARCHAR(200) NOT NULL,
            message VARCHAR(300) NOT NULL,
            date_envoi DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
}
register_activation_hook(__FILE__, 'contact_form_create_table');
// Register deactivation hook
register_deactivation_hook(__FILE__, 'my_plugin_deactivation');
function my_plugin_deactivation()
{
    global $wpdb;
    $wp_contact_form = $wpdb->prefix . 'contact_form';
    $wpdb->query("DROP TABLE IF EXISTS $wp_contact_form");
}

function my_plugin_enqueue_styles() {
    wp_enqueue_style( 'my-plugin-styles', plugin_dir_url( __FILE__ ) . 'css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_plugin_enqueue_styles' );

function my_custom_admin_css() {
  wp_enqueue_style( 'my-custom-admin-css', plugins_url( 'css/my-admin-style.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'my_custom_admin_css' );

function formulaire_contact_shortcode()
{
    ob_start(); // Commence la mise en cache du contenu de sortie
?>

<form id="contact-form' action="<?php echo esc_url( admin_url('admin-post.php') );?>" method="post">
        <div class="card">
            <h2>CONTACT US</h2>
            <div class="row">
            <div class="col">
                <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required="required">
                </div>
            </div>
        
            <div class="col">
                <div class="form-group">
                <label>Prenom</label>
                <input type="text" name="prenom" required="required">
                </div>
            </div>
        
            <div class="col">
                <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required="required">
                </div>
            </div>
            <div class="col">
            <div class="form-group">
            <label>Sujet</label>
            <input type="text" name="sujet" required="required">
            </div>
        </div>
            <div class="col">
                <div class="form-group">
                <label>Message</label>
                <textarea name="message" required="required"></textarea>
                </div>
            </div>
        
            <div class="col">
                <input type="submit" value="Submit" name="submitcontact">
            </div>
            </div>
        </div>
        </div>
		<?php
    //$output = "<form id="contact-form' action="<?php echo esc_url( admin_url('admin-post.php') );" method="post">



    $output = ob_get_clean(); // Termine la mise en cache et récupère le contenu
    return $output;
}
add_shortcode('formulaire_contact', 'formulaire_contact_shortcode');


function execute_on_init_event(){
	if(isset($_POST["submitcontact"])){
		$nom = $_POST["nom"];
		$prenom = $_POST["prenom"];
		$email = $_POST["email"];
		$sujet = $_POST["sujet"];
		$message = $_POST["message"];

		global $wpdb;
$date= current_time('mysql');
$table = $wpdb->prefix . 'contact_form';
$data = array('id' => NULL,'date_envoi' => $date, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'sujet' => $sujet, 'message' => $message );
$wpdb->insert($table,$data);
$result = $wpdb->insert($table, $data);

if ($result) {
    echo '<script>alert("Data inserted successfully.")</script>';
} else {
    echo '<script>alert("There was an error inserting the data.")</script>';
}
	}
}
// add the action
add_action( "init", "execute_on_init_event");
