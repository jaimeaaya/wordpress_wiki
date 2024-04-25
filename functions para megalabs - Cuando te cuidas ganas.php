<?php

/* custom JAYA */
// Función para cargar las tiendas
function cargar_tiendas() {
    global $wpdb;

    $idciudad = $_POST['idciudad']; // ID de la ciudad seleccionada

    // Consulta para obtener las tiendas según la ciudad seleccionada
    $tiendas = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT  * FROM wp_cs_tiendas where id in (select id_tienda from wp_cs_puntos_venta where id_ciudad = %d) ",
            $idciudad
        )
    );

    // Devolver las tiendas en formato JSON
    echo json_encode($tiendas);

    wp_die(); // Terminar el script
}
add_action('wp_ajax_cargar_tiendas', 'cargar_tiendas');
add_action('wp_ajax_nopriv_cargar_tiendas', 'cargar_tiendas'); 

// Función para cargar los puntos de venta
function cargar_puntos() {
    global $wpdb;

    $idciudad = $_POST['idciudad']; // ID de la ciudad seleccionado
	$idtienda = $_POST['idtienda']; // ID de la tienda seleccionado

    // Consulta para obtener los puntos según el departamento seleccionado
    $puntos = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT  * FROM wp_cs_puntos_venta where id_ciudad = %d AND id_tienda = %s ; ",
            $idciudad, $idtienda
        )
    );

    // Devolver las ciudades en formato JSON
    echo json_encode($puntos);

    wp_die(); // Terminar el script
}
add_action('wp_ajax_cargar_puntos', 'cargar_puntos');
add_action('wp_ajax_nopriv_cargar_puntos', 'cargar_puntos'); 

function agregar_scripts_personalizados() {

	if ( is_user_logged_in() ) {
		// El usuario está logeado
		?>
		<script>
			console.log('usuario loggeado');
			
			jQuery(document).ready(function($) {
				// no mostrar el formulario de login
				$('#inicia-sesion-home').css('display','none');
				$('#contenido-privado-home').css('display','block');
								
			});
		</script>
		<?php	

	} else {
		// El usuario no está logeado
		?>
		<script>
			console.log('usuario no loggeado');

			jQuery(document).ready(function($) {
				// no mostrar el formulario de login
				$('#inicia-sesion-home').css('display','block');
				$('#contenido-privado-home').css('display','none');
				
			});
		</script>
		<?php
	}

	if (is_page('registro-facturas')) {
		if ( current_user_can( 'manage_options' ) ) {
			// El usuario es administrador
			?>
			<script>
				console.log('El usuario es administrador');
				
			</script>
			<?php
		} else {
			// El usuario no es administrador
			?>
			<script>
				console.log('El usuario no es administrador');
				
			</script>
			<?php
			
			if ( is_user_logged_in() ) {
				// El usuario está logeado
				$user_id = get_current_user_id();
				$completado_autenticacion_doble = get_user_meta($user_id, 'completado_autenticacion_doble', true);

				if($completado_autenticacion_doble == 1){

					

					// El usuario está logeado
					global $wpdb; // Acceso a la base de datos de WordPress

					// Consulta para obtener las ciudades
					$ciudades = $wpdb->get_results("SELECT id, ciudad FROM wp_cs_ciudades");
					$options = '<option value="">Selecciona tu ciudad</option>';
					// Comprueba si hay resultados
					if ($ciudades) {
						foreach ($ciudades as $ciudad) {
							$options .= '<option value="' . $ciudad->id . '">' . $ciudad->ciudad . '</option>';
						}
					}
					/* 
					// Consulta para obtener las tiendas
					$tiendas = $wpdb->get_results("SELECT id, nombre FROM wp_cs_tiendas");
					$options_t == '<option value="">Selecciona tu tienda</option>';
					// Comprueba si hay resultados
					if ($tiendas) {
						foreach ($tiendas as $tienda) {
							$toptions_t .= '<option value="' . $tienda->id . '">' . $tienda->nombre . '</option>';
						}
					} 
					*/

					$user_id = get_current_user_id();

					// Consulta facturas por usuario
					$cuentas = $wpdb->get_results("SELECT count(entry_id) contador FROM cuandotecuidasganas.wp_evf_entries where user_id = '".$user_id."';");
					$totalfacturas = 0;
					
					// Comprueba si hay resultados
					if ($cuentas) {
						foreach ($cuentas as $cuenta) {
							$totalfacturas =  $cuenta->contador;
						}
					} 
					?>
					<script>
						
						jQuery(document).ready(function($) {
							
							var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

							///cuenta-facturas
							$('#cuenta-facturas').html('<h1 style="font-size:40px;"><strong><?php echo $totalfacturas;?> Facturas</strong><br/>registradas</h1>');
							
							//trae las ciudades
							$('#evf-218-field_eogdCuANvB-1').html('<?php echo $options;?>');
							
							//trae las tiendas
							//$('#evf-218-field_Ts4CAH6xYN-2').html('<?php //echo $toptions_t;?>');
							$('#evf-218-field_eogdCuANvB-1').change(function() {
								var ciudad_id = $(this).val();
								$('#evf-218-field_kujkqA7juK-11').val($('#evf-218-field_eogdCuANvB-1 option:selected').text());

								// Realizar la petición AJAX para traer las tiendas
								$.ajax({
									url: ajaxurl,
									type: 'POST',
									data: {
										action: 'cargar_tiendas',
										idciudad: ciudad_id
									},
									success: function(response) {
										var tiendas = JSON.parse(response);

										// Limpiar el selector de tiendas
										$('#evf-218-field_Ts4CAH6xYN-2').empty();
										$('#evf-218-field_Ts4CAH6xYN-2').append($('<option>', {
											value: '',
											text: 'Selecciona tu tienda'
										}));
										// Llenar el selector de tiendas con las tiendas obtenidas
										$.each(tiendas, function(index, tienda) {
											$('#evf-218-field_Ts4CAH6xYN-2').append($('<option>', {
												value: tienda.id,
												text: tienda.nombre
											}));
										});
									}
								});
							});

							//trae los puntos de venta 
							$('#evf-218-field_Ts4CAH6xYN-2').change(function() {
								var id_tienda = $(this).val();
								var id_ciudad = $('#evf-218-field_eogdCuANvB-1').val();
								$('#evf-218-field_GAXXndBXmZ-12').val($('#evf-218-field_Ts4CAH6xYN-2 option:selected').text());

								// Realizar la petición AJAX para traer los puntos
								$.ajax({
									url: ajaxurl,
									type: 'POST',
									data: {
										action: 'cargar_puntos',
										idciudad: id_ciudad,
										idtienda: id_tienda
									},
									success: function(response) {
										var tiendas = JSON.parse(response);

										// Limpiar el selector de tiendas
										$('#evf-218-field_G5WrUsrR3i-3').empty();
										$('#evf-218-field_G5WrUsrR3i-3').append($('<option>', {
											value: '',
											text: 'Selecciona tu punto de venta'
										}));	
										// Llenar el selector de tiendas con las tiendas obtenidas
										$.each(tiendas, function(index, tienda) {
											$('#evf-218-field_G5WrUsrR3i-3').append($('<option>', {
												value: tienda.id,
												text: tienda.punto
											}));
										});
									}
								});
							});

							$('#evf-218-field_G5WrUsrR3i-3').change(function() {
								$('#evf-218-field_G8ADW63ljH-13').val($('#evf-218-field_G5WrUsrR3i-3 option:selected').text());
							});					
						});
					</script>
					<?php
				}else{
					wp_logout();
					// Redirigir a la página de inicio o a cualquier otra página deseada después de cerrar sesión
					wp_redirect(home_url());
					exit();
				}	
			} else {
				// El usuario no está logeado
				wp_redirect(home_url());
			}
		}
	} //fin is page 'registro-facturas'

	if (is_page('login-val')) {
		if ( current_user_can( 'manage_options' ) ) {
			// El usuario es administrador
			?>
			<script>
				console.log('El usuario es administrador');
				
			</script>
			<?php
		} else {
			// El usuario no es administrador
			?>
			<script>
				console.log('El usuario no es administrador');
				
			</script>
			<?php
			
			if ( is_user_logged_in() ) {
				// El usuario está logeado
				$user_id = get_current_user_id();
				$current_user = wp_get_current_user();
				$email_current = $current_user->user_email;

				$codigo_guardado = get_user_meta($user_id, 'autenticacion_codigo', true); 
				$completado_autenticacion_doble = get_user_meta($user_id, 'completado_autenticacion_doble', true);

				?>
				<script>
					//console.log("<?php echo 'completado_autenticacion_doble: '.$completado_autenticacion_doble; ?>");
					//console.log("<?php echo 'codigo_guardado: '.$codigo_guardado; ?>");
					
					jQuery(document).ready(function($) {
						console.log('valida y muestra el correo encriptado');
						var comprobacion = "<?php echo $valido; ?>";
						var correoOriginal = "<?php echo $email_current; ?>";

						// Separar la parte del nombre de usuario y el dominio
						var partes = correoOriginal.split("@");
						var nombreUsuario = partes[0];
						var dominio = partes[1];

						// Camuflar el nombre de usuario
						var nombreUsuarioCamuflado = nombreUsuario.charAt(0) + "*".repeat(nombreUsuario.length - 2) + nombreUsuario.charAt(nombreUsuario.length - 1);

						// Camuflar el dominio
						var dominioCamuflado = dominio.charAt(0) + "****.***";

						// Mostrar el correo electrónico camuflado en la página
						$("#correoencriptado").text(nombreUsuarioCamuflado + "@" + dominioCamuflado);
										
					});
				</script>
				<?php

				$valido = 0;
				if (isset($_POST['auth_code']) ) { //&& $completado_autenticacion_doble == 0
					
					$codigo_ingresado = $_POST['auth_code'];

					?>
					<script>
						//console.log('entra a la validacion de codigo');
						//console.log("<?php echo 'codigo_ingresado: '.$codigo_ingresado; ?>");
						//console.log("<?php echo 'codigo_guardado: '.$codigo_guardado; ?>");
					</script>
					<?php
					
					if ($codigo_guardado && $codigo_ingresado == $codigo_guardado) {
						// Código correcto, permitir el inicio de sesión
						update_user_meta($user_id, 'completado_autenticacion_doble', 1);
						wp_redirect('/registro-facturas');
					} else {
						// Código incorrecto, mostrar un mensaje de error
						$respuesta = 'El código de autenticación es incorrecto';
						
						?>
						<script>						
							jQuery(document).ready(function($) {
								var loginurl = '/login';
								//console.log('codigo no valido');
								$("#preformulario").html("<?php echo $respuesta; ?>");
								$("#validar_codigo").html('<center>¿No recibiste el código? <a href="<?php echo wp_logout_url(  home_url() ); ?>"  style="color: var(--e-global-color-primary);font-weight: 900;">Reenviar código</a></center>');
																			
							}); 

						</script>
						<?php
					}
				}else{
					?>
				<script>
					//console.log('no entra');
					
				</script>
				<?php
				}
				
			} else {
				// El usuario no está logeado
				wp_redirect(home_url());
			} // fin si está logeado	
		}
	} //fin is page 'login-val'

}
add_action('wp_footer', 'agregar_scripts_personalizados');

add_action( 'um_on_login_before_redirect', 'my_on_login_before_redirect', 10, 1 );
function my_on_login_before_redirect( $user_id ) {

	$user = get_userdata($user_id);

    $email = $user->user_email;
	$codigo = generar_codigo_autenticacion(); 
	update_user_meta($user_id, 'autenticacion_codigo', $codigo); 
	update_user_meta($user_id, 'completado_autenticacion_doble', 0);

    $mensaje = '¡Bienvenido de nuevo, ' . $user->display_name . '! Gracias por iniciar sesión. ';
	$subject = 'Código de autenticación para iniciar sesión';
    $mensaje .= 'Su código de autenticación es: ' . $codigo;

    wp_mail($email, $subject, $mensaje);

	$pagina_redireccion = '/login-val'; 

    // Redirigir a la URL con el ID de usuario como parámetro
    wp_redirect($pagina_redireccion);
    exit();
}


// Función para generar un código aleatorio
function generar_codigo_autenticacion() {
    return substr(str_shuffle("0123456789"), 0, 4); // Generar un código de 4 dígitos
}
