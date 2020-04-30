<?php

/**
 * Plugin Name: Contact setting
 * Description: Добавляет в консоль контактные данные, для вывода их на сайте
 * Plugin URI:  dds.by
 * Author URI:  https://profiles.wordpress.org/dinosaurdesignstudio/
 * Author:      dinosaurdesignstudio
 *
 * Text Domain: Идентификатор перевода. Пр: my-plugin
 * Domain Path: Путь до MO файла перевода относительно папки плагина.
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Network:     Укажите true, чтобы активировать плагин для Мультисайта.
 * Version:     1.0
 */
$dds_page = 'dds_parametrs'; // это часть URL страницы, рекомендую использовать строковое значение, т.к. в данном случае не будет зависимости от того, в какой файл вы всё это вставите

/*
 * Функция, добавляющая страницу в пункт меню Настройки
 */
function dds_options() {
	global $dds_page;
	add_options_page( 'Контакты', 'Контакты', 'manage_options', $dds_page, 'dds_option_page' );
}

add_action( 'admin_menu', 'dds_options' );

/**
 * Возвратная функция (Callback)
 */
function dds_option_page() {
	global $dds_page;
	?>
	<div class="wrap">
	<h2>Контактные данные</h2>
	<form method="post" enctype="multipart/form-data" action="options.php">
		<?php
		settings_fields( 'dds_options' ); // меняем под себя только здесь (название настроек)
		do_settings_sections( $dds_page );
		?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'theme_otip_vstu' ) ?>"/>
		</p>
	</form>
	</div><?php
}

/*
 * Регистрируем настройки
 * Мои настройки будут храниться в базе под названием dds_options (это также видно в предыдущей функции)
 */
function dds_option_settings() {
	global $dds_page;
	// Присваиваем функцию валидации ( dds_validate_settings() ). Вы найдете её ниже
	register_setting( 'dds_options', 'dds_options', 'dds_validate_settings' ); // dds_options

	// Добавляем секцию
	add_settings_section( 'dds_section_1', 'Контактные данные', '', $dds_page );

	// Создадим текстовое поле в первой секции
	$dds_field_params = array(
		'type'      => 'text',
		'id'        => 'my_adres',
		'desc'      => 'Пример обычного текстового поля.',
		'label_for' => 'my_adres'
	);
	add_settings_field( 'my_adres_field', 'Адрес', 'dds_option_display_settings', $dds_page, 'dds_section_1', $dds_field_params );
	// Создадим текстовое поле в первой секции
	$dds_field_params = array(
		'type'      => 'email', // тип
		'id'        => 'my_mail',
		'desc'      => 'ex@example.com',
		'label_for' => 'my_mail'
	);
	add_settings_field( 'my_mail_field1', 'e-mail', 'dds_option_display_settings', $dds_page, 'dds_section_1', $dds_field_params );
	// Создадим текстовое поле в первой секции
	$dds_field_params = array(
		'type'      => 'tel',// тип
		'id'        => 'my_tel',
		'desc'      => '333-33-33',
		'label_for' => 'my_tel'
	);
	add_settings_field( 'my_tel_field', 'Телефон', 'dds_option_display_settings', $dds_page, 'dds_section_1', $dds_field_params );

}

add_action( 'admin_init', 'dds_option_settings' );

/*
 * Функция отображения полей ввода
 * Здесь задаётся HTML и PHP, выводящий поля
 */
function dds_option_display_settings( $args ) {
	extract( $args );

	$option_name = 'dds_options';

	$o = get_option( $option_name );

	switch ( $type ) {
		case 'text':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";
			echo ( $desc != '' ) ? "<br /><span class='description'>$desc</span>" : "";
			break;
		case 'email':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input class='regular-text' type='email' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";
			echo ( $desc != '' ) ? "<br /><span class='description'>$desc</span>" : "";
			break;
		case 'tel':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input class='regular-text' type='tel' id='$id'  name='" . $option_name . "[$id]' value='$o[$id]' />";
			echo ( $desc != '' ) ? "<br /><span class='description'>$desc</span>" : "";
			break;
	}
}

/*
 * Функция проверки правильности вводимых полей
 */
function dds_validate_settings( $input ) {
	foreach ( $input as $k => $v ) {
		$valid_input[ $k ] = trim( $v );

		/* Вы можете включить в эту функцию различные проверки значений, например
		if(! задаем условие ) { // если не выполняется
			$valid_input[$k] = ''; // тогда присваиваем значению пустую строку
		}
		*/
	}

	return $valid_input;
}

$all_options = get_option( 'dds_options' ); // это массив
