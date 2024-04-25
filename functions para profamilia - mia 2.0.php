<?php
add_action('after_setup_theme', 'uncode_language_setup');
function uncode_language_setup()
{
	load_child_theme_textdomain('uncode', get_stylesheet_directory() . '/languages');
}

function theme_enqueue_styles()
{
	$production_mode = ot_get_option('_uncode_production');
	$resources_version = ($production_mode === 'on') ? null : rand();
	$parent_style = 'uncode-style';

	wp_enqueue_style($parent_style, get_template_directory_uri() . '/library/css/style.css', array(), $resources_version);
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(), $resources_version);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');


/**/

add_action("wp_enqueue_scripts", "dcms_insertar_js",100);

function dcms_insertar_js(){
    
    wp_register_script('miscript', get_stylesheet_directory_uri(). '/includes/js/profam-script.js', array('jquery'), '1', true );
    

    $ruta = get_template_directory_uri().'-child/includes/profamWservices.php';
    $idusuario =  get_current_user_id();
  
    $data_usr =  wp_get_current_user();
    $user_login = $data_usr->user_login;
    $email_current = $data_usr->user_email;
   // $correoUtil = $_COOKIE["correoUtil"];
    
    $fecha_hoy = date("Y-m-d");
    $hora_ya = date("H:i");
    // Env¨ªamos el array al script. Estar¨¢ guardado en un objeto de JavaScript llamado WP_OPTIONS
    wp_localize_script( 'miscript', 'my_javascript_object', array('ruta' => $ruta , 'usuario' => $idusuario  , 'user_login' => $user_login, 'current_email' => $email_current, 'fecha_hoy' => $fecha_hoy, 'hora_ya' => $hora_ya) );

    
    wp_enqueue_script('miscript');
    
}

function agregar_recursos_datepicker() {
    // Registra jQuery UI (si a¨²n no est¨¢ registrado)
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');

    // Agrega los estilos del datepicker
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}

add_action('wp_enqueue_scripts', 'agregar_recursos_datepicker');

//Redirigir al chekcout sin pasar por el carrito
//add_filter ('add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
	global $woocommerce;
	$checkout_url = $woocommerce->cart->get_checkout_url();
	return $checkout_url;
}

function redirect_carrito_to_checkout(){
    if (is_page(3978)){
        wp_redirect( get_permalink( 3979 ) );
        die;
    }
}

//add_action( 'template_redirect', 'redirect_carrito_to_checkout' );

function vaciar_carrito_al_salir() {
    if( function_exists('WC') ){
        WC()->cart->empty_cart();
    }
}
add_action('wp_logout', 'vaciar_carrito_al_salir');

//Limita el carrito de WooCommerce a un único producto

add_filter( 'woocommerce_add_cart_item_data', 'mk_only_one_item_in_cart', 10, 1 );

function mk_only_one_item_in_cart( $cartItemData ) {
	wc_empty_cart();

	return $cartItemData;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('woocommerce_add_to_cart_redirect', 'redireccionar_a_checkout_con_parametro_personalizado');

function redireccionar_a_checkout_con_parametro_personalizado($url) {
    global $woocommerce;

    // Obtener el ID del producto agregado al carrito
    $product_id = absint($_POST['add-to-cart']);

    // Obtener el valor personalizado que deseas pasar
    $id_conf      = $_REQUEST['id_conf'];
    $id_form_prev = $_REQUEST['idformprv'];

    // Agregar el valor personalizado como metadato al producto en el carrito
    $cart_item_key = $woocommerce->cart->add_to_cart($product_id, 1, 0, array(), array('id_conf' => $id_conf, 'id_form_prev' => $id_form_prev));

    // Construir la URL de redirección al formulario de checkout
    $checkout_url = wc_get_checkout_url();

    // Agregar el parámetro personalizado a la URL de redirección
    $checkout_url = add_query_arg('id_conf', urlencode($id_conf), $checkout_url);
    $checkout_url = add_query_arg('id_form_prev', urlencode($id_form_prev), $checkout_url);
    
    return $checkout_url;
}

////////////////////////
function validar_parametros_url() {
    global $wpdb;
    
    //formulario preregistro
    if (is_page('4172')) { 
        
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script type="text/javascript" id="validar_respuestas_preregistro">
        
        
            jQuery(document).ready(function($) {
				
				//método de pago
                var metodopago = $('#evf-4171-field_OPdhKMDZBU-25');
                metodopago.change(function() {
                    
                    var opcionmetodopago = metodopago.val();
                    
                    if(opcionmetodopago === 'Otro'){
                    //if(opcionmetodopago != 'PSE' || opcionmetodopago.substring(0, 2) != 'TC' || opcionmetodopago.substring(0, 2) != 'TD'){
                        //alert('Opción no válida para continuar');
                        $('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-metodopago',''));
                        
                    }else{
                        $('.evf-submit-container').css('display','block');
                        $('#boton-abre-modal-edadgestacional').css('display','none');
                    }
                });
				//pregunta estas en embarazo
				$('input[name="everest_forms[form_fields][hNCiId5z72-27]"]').change(function() {
					var valorSeleccionado = $('input[name="everest_forms[form_fields][hNCiId5z72-27]"]:checked').val();
					if (valorSeleccionado === 'Si') {
						console.log('Se seleccionó la opción Si esta en embarazo');
						$('.evf-submit-container').css('display','block');
                        $('#boton-abre-modal-edadgestacional').css('display','none');
					  
					} else if (valorSeleccionado === 'No') {
						console.log('Se seleccionó la opción No esta en embarazo');
						$('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-no-embarazo',''));
					} 
				});
				
				//pregunta intentaste interrumpir tu embarazo
				$('input[name="everest_forms[form_fields][FZQrT65Iiv-28]"]').change(function() {
					var valorSeleccionado = $('input[name="everest_forms[form_fields][FZQrT65Iiv-28]"]:checked').val();
					if (valorSeleccionado === 'No') {
						console.log('Se seleccionó la opción No intentaste interrumpir tu embarazo');
						$('.evf-submit-container').css('display','block');
                        $('#boton-abre-modal-edadgestacional').css('display','none');
					  
					} else if (valorSeleccionado === 'Si') {
						console.log('Se seleccionó la opción Si intentaste interrumpir tu embarazo');
						$('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-interrumpir-embarazo',''));
					} 
				});
								
				//pregunta Tus ciclos menstruales son regulares
				$('input[name="everest_forms[form_fields][VhTinMNmNF-30]"]').change(function() {
					var valorSeleccionado = $('input[name="everest_forms[form_fields][VhTinMNmNF-30]"]:checked').val();
					if (valorSeleccionado === 'Si') {
						console.log('Se seleccionó la opción Si Tus ciclos menstruales son regulares');
						$('.evf-submit-container').css('display','block');
                        $('#boton-abre-modal-edadgestacional').css('display','none');
					  
					} else if (valorSeleccionado === 'No') {
						console.log('Se seleccionó la opción No Tus ciclos menstruales son regulares');
						$('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-ciclos-regulares',''));
					} 
				});
				
				
				var fechaActual = new Date();
				var yearactual = fechaActual.getFullYear();
				
				//pregunta fecha nacimiento
				$('#year-select-evf-4171-field_WNE3Dlm40y-31 option').filter(function() {
					return parseInt($(this).val(), 10) > yearactual-10;
				}).remove();
				
				$('#day-select-evf-4171-field_WNE3Dlm40y-31 option').filter(function() {
					return parseInt($(this).val(), 10) > 31;
				}).remove();
				
				//pregunta edad gestacional
				$('#year-select-evf-4171-field_nA6KcHEEsN-32 option').filter(function() {
					return parseInt($(this).val(), 10) > yearactual;
				}).remove();
				
				$('#year-select-evf-4171-field_nA6KcHEEsN-32 option').filter(function() {
					return parseInt($(this).val(), 10) < yearactual-1;
				}).remove();
				
				$('#day-select-evf-4171-field_nA6KcHEEsN-32 option').filter(function() {
					return parseInt($(this).val(), 10) > 31;
				}).remove();
								
				
                //validar si es por particular o por EPS
                var tipoafiliacion = $('#evf-4171-field_buFxndbhx0-34');
               
                
                tipoafiliacion.change(function() {
                    
                    var opcionSeleccionadatipoafiliacion = $(this).val();
                    
                    if(opcionSeleccionadatipoafiliacion != 'Particular'){
                        //alert('Opción no válida para continuar');
                        $('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-metodopago',''));
                        
                    }else{
                        $('.evf-submit-container').css('display','block');
                        $('#boton-abre-modal-edadgestacional').css('display','none');
                    }
                });
                
                //Validar por edad gestacional
                var year_ultperiodo  = $('#year-select-evf-4171-field_nA6KcHEEsN-32');
                var month_ultperiodo = $('#month-select-evf-4171-field_nA6KcHEEsN-32');
                var day_ultperiodo   = $('#day-select-evf-4171-field_nA6KcHEEsN-32');
                
                day_ultperiodo.change(function() {

                    var dia = parseInt(day_ultperiodo.val());
                    var mes = parseInt(month_ultperiodo.val()) - 1; // Restamos 1 ya que los meses en JavaScript son de 0 a 11
                    var anio = parseInt(year_ultperiodo.val());
                
                    var fechaSeleccionada = new Date(anio, mes, dia);
                    var fechaActual = new Date();
                    
                    $('#resultado').css('display','block');
                     
                    // Verificar si la fecha ingresada es válida
                    if (isNaN(fechaSeleccionada)) {
                        //alert( "Fecha no válida.");
                        document.getElementById("resultado").innerHTML = "Fecha no válida.";
                        return;
                    }
                
                    // Calcular la diferencia en milisegundos
                    var diferenciaEnMs = fechaActual - fechaSeleccionada;
                
                    // Convertir la diferencia a semanas y días
                    var semanasDiferencia = Math.floor(diferenciaEnMs / (1000 * 60 * 60 * 24 * 7));
                    var diasDiferencia = Math.floor((diferenciaEnMs % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));
                    
					if (semanasDiferencia < 0 ) {
                        //alert( "Fecha no válida.");
                        document.getElementById("resultado").innerHTML = "Fecha no válida.";
						$('.evf-submit-container').css('display','none');
                        $('#boton-abre-modal-edadgestacional').css('display','block');
                        $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-edadgestacional-error',''));
                        
                    }
					var resultado_calc = '';
                    if(semanasDiferencia >= 0 && semanasDiferencia <= 40){
						
						resultado_calc = "<b>Tu edad gestacional es:</b><br/> " + semanasDiferencia + " semanas, " + diasDiferencia + " días.";
                        document.getElementById("resultado").innerHTML = resultado_calc;
						
                        
                        if( semanasDiferencia >= 10){
                            $('.evf-submit-container').css('display','none');
                            $('#boton-abre-modal-edadgestacional').css('display','block');
                            $('#boton-abre-modal-edadgestacional').on('click', openModal('alerta-edadgestacional',resultado_calc));
							document.getElementById("adicional-modal").innerHTML = resultado_calc;
							
                            //alert( "Tu edad gestacional supera las 10 semanas, por favor comunícate lo antes posible con una de nuestras asesoras por Whatsapp para ofrecerte un mejor acompañamiento.");
                        }else{
                            $('.evf-submit-container').css('display','block');
                            $('#boton-abre-modal-edadgestacional').css('display','none');
                        }
                    }
               
                });
                
            });
        
        /* VENTANAS MODALES PARA PREREGISTRO */

        // obtiene modal por la clase para editar su css
        let modalId = document.querySelector(".modal");
        
        //obtiene el contenido del modal el cual se reemplaza mediante la funcion openModal
        let contentModal = document.getElementById("content-modal");
        let adicionalModal = document.getElementById("adicional-modal");
		
        // obtiene el overlay del modal
        const overlay = document.querySelector(".overlay-modal");
        
        // obtiene el boton con la funcion onclick para abrir el modal
        const openModalBtn = document.querySelector(".btn-open");
        
        // obtiene el boton para cerrar el boton
        //const closeModalBtn = document.querySelector(".btn-close");
        
        
        // estas variables html + numero contienen el html dinamico para un contenido de modal especifico
         
        let html0 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Tu edad gestacional no es válida, por favor verifica y cambia la fecha.</p><br></div>";
		
		let html1 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Tu edad gestacional supera las 10 semanas. Por favor comunícate lo antes posible con una de nuestras personas de soporte en el ícono de chat en la parte inferior, para ofrecerte un mejor acompañamiento o escríbenos por Whatsapp.</p><br> <p id ='adicional-modal'></p><br>  <center><a id='boton-linea-whatsapp' href='https://wa.me/573187351722'><i class='fab fa-whatsapp'></i> Contacto</a></center></div>";
        
        let html2 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Debes comunicarte con la línea de atención al usuario de Profamilia para una atención más personalizada.</p><br><center><a id='boton-linea-whatsapp' href='https://wa.me/573187351722'><i class='fab fa-whatsapp'></i> Contacto</a></center></div>";
        
        let html3 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Lo sentimos, no puedes continuar sin haber realizado una prueba de embarazo. <br/> <br/>Lo más recomendable en este caso es primero realizar una prueba de embarazo preferiblemente en sangre, estas pruebas son confiables si se hacen después de 15 días de la relación de riesgo, es decir, donde no te protegiste o se rompió el preservativo, si el resultado es negativo, se aconseja repetir la prueba con 15 días de abstinencia sexual. Una vez confirmes el embarazo te puedes volver a comunicar con nosotros.</p></div>";
        
		let html4 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Comunícate lo antes posible con una de nuestras personas de soporte en el ícono de chat en la parte inferior, para ofrecerte un mejor acompañamiento o escríbenos por Whatsapp.</p><br><center><a id='boton-linea-whatsapp' href='https://wa.me/573187351722'><i class='fab fa-whatsapp'></i> Contacto</a></center></div>";
		
		let html5 = "<div class='modal-text'><h2 style='color:#0ABF76'>Atención</h2> <img class='modal-img' src='/wp-content/uploads/2023/12/image-48.png'/><p>Te recomendamos hacerte una ecografía para saber con exactitud tu edad gestacional. Si ya sabes tu edad gestacional, ingrésala en el formulario, de lo contrario no podrás continuar</p></div>";
        
        // open modal function
        // Esta es la funcion que abre el modal, recibe el parametro id que es el identificador del contenido a abrir
        
        const openModal = function (id, adicional) {
        
			// variable que inserta el contenido html en el contenido del modal de acuerdo al parametro de modal ingresado
			let content  = "";
			
			// validacion del parametro id para asignar el contenido html a la variable contenido
			switch (id) {
			  case 'alerta-edadgestacional':
				content = html1;
				break;
			  case 'alerta-edadgestacional-error':
				content = html0;
				break;	
			  case 'alerta-metodopago':
				content = html2;
				break;   	
			  case 'alerta-no-embarazo':
				content = html3;
				break;
			  case 'alerta-interrumpir-embarazo':
				content = html4;
				break;
			  case 'alerta-ciclos-regulares':
				content = html5;
				break;
			}
			
			// quita el hidden del css del modal y el overlay para que se muestren
			  modalId.classList.remove("hidden-modal");
			  overlay.classList.remove("hidden-modal");
			
			// se inserta el html en el contenido del modal
			  contentModal.innerHTML = content;

			  setTimeout(function() {
				window.location.href = "/quiero-comenzar-el-proceso-de-aborto-ya/"; // Cambiar a la URL deseada
			  }, 10000); // 10000 milisegundos = 10 segundos
	
        };
        
        // open modal event
        //openModalBtn.addEventListener("click", openModal(modalId));
        
/*        
        // close modal function
        const closeModal = function () {
        
          modalId.classList.add("hidden-modal");
          overlay.classList.add("hidden-modal");
          //contentModal.removeChild()
        };
        
        // cierra el modal cuando se da click al overlay
        closeModalBtn.addEventListener("click", closeModal);
        overlay.addEventListener("click", closeModal);
        
        // close modal when the Esc key is pressed
        document.addEventListener("keydown", function (e) {
          if (e.key === "Escape" && !modalId.classList.contains("hidden-modal")) {
            closeModal();
          }
        });
*/
        </script>

        <style>
            /**************
            *ventana modal*
            ***************/
            
            .container-cards{
                display:flex;
                justify-content:center;
            }
            
            .card-item{
                width:100%;
                max-width:230px;
                margin:10px 15px;
                transition:all .4s;
            }
            
            .card-item:hover{
                transform:scale(1.05);
            }
            
            
            .modal {
              display: block;
              width:100%;
            
              max-width: 900px;
              padding: 1.6rem;
              height:auto;
              position: fixed;
              z-index: 99999999!important;
              top: 20%;
              background-color: #FFFAE6;
              border: 1px solid #ddd;
              border-radius: 25px;
              transition:all .4s;
              top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            }
            
            .modal .flex {
              display: flex;
              align-items: center;
              justify-content: center;
              gap:20px
            }
            
            
            .modal p {
              font-size: 13px;
              color: #000;
              margin: 0.4rem 0 0.2rem;
            }
            
            
            .btn-open {
              cursor:pointer;
            }
            
            .btn-close {
                /* transform: translate(10px, -20px); */
                padding: 0.5rem 0.7rem;
                background: #eee;
                border-radius: 50%;
                /* flex-basis: revert; */
                margin-left: auto;
                z-index:9999999999;
            
            }
            
            .modal-img{
                width:150px;
                text-align: center;
                margin: 0 auto;
                padding: 20px;
            }
            
            .modal-text{
                text-align: center;
            }
            
            .overlay-modal {
              position: fixed;
              top: 0;
              bottom: 0;
              left: 0;
              right: 0;
              width: 100%;
              height: 100%;
              background: rgba(0, 0, 0, 0.5);
              backdrop-filter: blur(6px);
              z-index: 9999;
            }
            
            .hidden-modal {
              display: none;
            }
            
            @keyframes fadeIn {
              0% { opacity: 0; }
              100% { opacity: 1; }
            }
            
            .fade-in-div { animation: fadeIn 0.7s; }
            
            
            @media (max-width:720px){
            
            .container-cards{
                display: table;
                justify-content: center;
            }
            
            
            .card-item {
                width: 50%;
                max-width: auto;
                margin: 0px 15px;
                transition: all .4s;
                display: table-cell;
                padding-left: 10px;
            }
            
            .modal-text h2{
                font-size:20px!important;
            }
            
            .card-item .img-center{
                margin: auto;
                display: block;
                width: 50%;
            }
            
            
            
            }
        </style>
        
        <section class="modal hidden-modal fade-in-div">

           <!-- En este div se inserta el contenido por js -->
          <div id="content-modal" class="flex" style="margin-top:-50px;">
             
          </div>
            
        </section>
        
        <!-- overlay del modal -->
        <div class="overlay-modal hidden-modal"></div>
        
        <?php
    }
    
    //formulario agendamiento
    if (is_page('3900')) { 
        
        // Obtiene los parámetros de la URL
        $parametro1 = isset($_GET['mch']) ? sanitize_text_field($_GET['mch']) : '';
        $parametro2 = isset($_GET['eps']) ? sanitize_text_field($_GET['eps']) : '';
        $parametro3 = isset($_GET['fenh']) ? sanitize_text_field($_GET['fenh']) : '';
        $parametro4 = isset($_GET['numdoc']) ? sanitize_text_field($_GET['numdoc']) : '';

        if (!empty($parametro1) && !empty($parametro2) && !empty($parametro3) && !empty($parametro4)) {
           
            try {
                $fecha_nacimiento = $parametro3;
                
                $fecha_nacimiento_obj = new DateTime($fecha_nacimiento);
                
                $fecha_actual = new DateTime();
                
                
                
                $diferencia = $fecha_nacimiento_obj->diff($fecha_actual);
                
                $edad = $diferencia->y;
            
            } catch (Exception $e) {
                // Capturar la excepción en caso de un error
                //echo "Error al analizar la fecha: " . $e->getMessage();
                $edad = 19;
            }

            if($edad <= 18){
                $producto = 4198;
            }else{
                $producto = 3984;
            }
            
            
             $query = "SELECT MAX(entry_id) ultimo FROM `wp_evf_entrymeta`  where meta_key = 'documento' and meta_value = '$parametro4';";

            // Ejecuta la consulta y obtén los resultados
            $resultados = $wpdb->get_results($query);
        
            // Verifica si hay resultados
            if ($resultados) {
                // Itera sobre los resultados y muestra los títulos
                foreach ($resultados as $post) {
                    $entry_id = $post->ultimo;
                }
            }
            
            
            ?>
            <script type="text/javascript" id="validar_parametros_url">
                jQuery(document).ready(function($) {
                    
                    $('#evf-3902-field_KhKCxCMjyd-20').val('<?php echo $producto; ?>');
                    $('#evf-3902-field_ALDjjdnkLX-21').val('<?php echo $entry_id; ?>');
                    
                    console.log('debe asignar el id de producto <?php echo $producto; ?>');
                    console.log('edad del usuario <?php echo $edad; ?>');
                    console.log('id form datos previos <?php echo $entry_id; ?>');
                    
                });
            </script>
            <?php
            
            
            
            if($parametro1 === 'Otro' || $parametro2 != 'Particular' ){
                
                ?>
                <script type="text/javascript" id="validar_parametros_url_pse_particular">
                
                    jQuery(document).ready(function($) {
                        
                        // deshabilitamos el formulario de agendamiento
                        $('#formu-agendamiento').hide();
                        $('#agendamiento-no').show();
                        
                        console.log('debe mostrar mensaje de atencion al usuario');
                        
                    });
                    
                </script>
                <?php
                
            }
            
        } else {
            
            //si no viene con los datos del preregistro debe devolverlo
            header('Location: ' . '/formulario-medico-datos-basicos');
            
        }
        
        ?>
        <style>
            /**************
            *ventana modal*
            ***************/
            
            .container-cards{
                display:flex;
                justify-content:center;
            }
            
            .card-item{
                width:100%;
                max-width:230px;
                margin:10px 15px;
                transition:all .4s;
            }
            
            .card-item:hover{
                transform:scale(1.05);
            }
            
            
            .modal {
              display: block;
              width:100%;
            
              max-width: 900px;
              padding: 1.6rem;
              height:auto;
              position: fixed;
              z-index: 99999999!important;
              top: 20%;
              background-color: #FFFAE6;
              border: 1px solid #ddd;
              border-radius: 25px;
              transition:all .4s;
              top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            }
            
            .modal .flex {
              display: flex;
              align-items: center;
              justify-content: center;
              gap:20px
            }
            
            
            .modal p {
              font-size: 13px;
              color: #000;
              margin: 0.4rem 0 0.2rem;
            }
            
            
            .btn-open {
              cursor:pointer;
            }
            
            .btn-close {
                /* transform: translate(10px, -20px); */
                padding: 0.5rem 0.7rem;
                background: #eee;
                border-radius: 50%;
                /* flex-basis: revert; */
                margin-left: auto;
                z-index:9999999999;
            
            }
            
            .modal-img{
                width:150px;
                text-align: center;
                margin: 0 auto;
                padding: 20px;
            }
            
            .modal-text{
                text-align: center;
            }
            
            .overlay-modal {
              position: fixed;
              top: 0;
              bottom: 0;
              left: 0;
              right: 0;
              width: 100%;
              height: 100%;
              background: rgba(0, 0, 0, 0.5);
              backdrop-filter: blur(6px);
              z-index: 9999;
            }
            
            .hidden-modal {
              display: none;
            }
            
            @keyframes fadeIn {
              0% { opacity: 0; }
              100% { opacity: 1; }
            }
            
            .fade-in-div { animation: fadeIn 0.7s; }
            
            
            @media (max-width:720px){
            
            .container-cards{
                display: table;
                justify-content: center;
            }
            
            
            .card-item {
                width: 50%;
                max-width: auto;
                margin: 0px 15px;
                transition: all .4s;
                display: table-cell;
                padding-left: 10px;
            }
            
            .modal-text h2{
                font-size:20px!important;
            }
            
            .card-item .img-center{
                margin: auto;
                display: block;
                width: 50%;
            }
            
            
            
            }
        </style>
        
        <section class="modal hidden-modal fade-in-div">
          <div class="flex">
            <!-- Boton cerrar modal -->
            
          </div>
           <!-- En este div se inserta el contenido por js -->
          <div id="content-modal" class="flex" style="margin-top:-50px;">
             
          </div>
            
        </section>
        
        <!-- overlay del modal -->
        <div class="overlay-modal hidden-modal"></div>
        <script>
            // obtiene modal por la clase para editar su css
            let modalId = document.querySelector(".modal");
            
            //obtiene el contenido del modal el cual se reemplaza mediante la funcion openModal
            let contentModal = document.getElementById("content-modal");
            
            // obtiene el overlay del modal
            const overlay = document.querySelector(".overlay-modal");
            
            // obtiene el boton con la funcion onclick para abrir el modal
            const openModalBtn = document.querySelector(".btn-open");
            
            // obtiene el boton para cerrar el boton
            const closeModalBtn = document.querySelector(".btn-close");
            
            
             
            // open modal function
            // Esta es la funcion que abre el modal, recibe el parametro id que es el identificador del contenido a abrir
            
            const openModal = function (content) {
            
            // quita el hidden del css del modal y el overlay para que se muestren
              modalId.classList.remove("hidden-modal");
              overlay.classList.remove("hidden-modal");
            
            // se inserta el html en el contenido del modal
              contentModal.innerHTML = content;
            };
            
            // open modal event
            //openModalBtn.addEventListener("click", openModal(modalId));
            
            /*
            // close modal function
            const closeModal = function () {
            
              modalId.classList.add("hidden-modal");
              overlay.classList.add("hidden-modal");
              //contentModal.removeChild()
            };
            
            // cierra el modal cuando se da click al overlay
            closeModalBtn.addEventListener("click", closeModal);
            overlay.addEventListener("click", closeModal);
            
            // close modal when the Esc key is pressed
            document.addEventListener("keydown", function (e) {
              if (e.key === "Escape" && !modalId.classList.contains("hidden-modal")) {
                closeModal();
              }
            });
			*/
        </script>
        <?php
    }
    
    //formulario checkout
    if (is_page('3979')) {
        ?>
        <style>
            /**************
            *ventana modal*
            ***************/
            
            .container-cards{
                display:flex;
                justify-content:center;
            }
            
            .card-item{
                width:100%;
                max-width:230px;
                margin:10px 15px;
                transition:all .4s;
            }
            
            .card-item:hover{
                transform:scale(1.05);
            }
            
            
            .modal {
              display: block;
              width:100%;
            
              max-width: 900px;
              padding: 1.6rem;
              height:auto;
              position: fixed;
              z-index: 99999999!important;
              top: 20%;
              background-color: #FFFAE6;
              border: 1px solid #ddd;
              border-radius: 25px;
              transition:all .4s;
              top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            }
            
            .modal .flex {
              display: flex;
              align-items: center;
              justify-content: center;
              gap:20px
            }
            
            
            .modal p {
              font-size: 13px;
              color: #000;
              margin: 0.4rem 0 0.2rem;
            }
            
            
            .btn-open {
              cursor:pointer;
            }
            
            .btn-close {
                /* transform: translate(10px, -20px); */
                padding: 0.5rem 0.7rem;
                background: #eee;
                border-radius: 50%;
                /* flex-basis: revert; */
                margin-left: auto;
                z-index:9999999999;
            
            }
            
            .modal-img{
                width:150px;
                text-align: center;
                margin: 0 auto;
                padding: 20px;
            }
            
            .modal-text{
                text-align: center;
            }
            
            .overlay-modal {
              position: fixed;
              top: 0;
              bottom: 0;
              left: 0;
              right: 0;
              width: 100%;
              height: 100%;
              background: rgba(0, 0, 0, 0.5);
              backdrop-filter: blur(6px);
              z-index: 9999;
            }
            
            .hidden-modal {
              display: none;
            }
            
            @keyframes fadeIn {
              0% { opacity: 0; }
              100% { opacity: 1; }
            }
            
            .fade-in-div { animation: fadeIn 0.7s; }
            
            
            @media (max-width:720px){
            
            .container-cards{
                display: table;
                justify-content: center;
            }
            
            
            .card-item {
                width: 50%;
                max-width: auto;
                margin: 0px 15px;
                transition: all .4s;
                display: table-cell;
                padding-left: 10px;
            }
            
            .modal-text h2{
                font-size:20px!important;
            }
            
            .card-item .img-center{
                margin: auto;
                display: block;
                width: 50%;
            }
            
            
            
            }
        </style>
        
        <section class="modal hidden-modal fade-in-div">
          <div class="flex">
            <!-- Boton cerrar modal -->
          </div>
           <!-- En este div se inserta el contenido por js -->
          <div id="content-modal" class="flex" style="margin-top:-50px;">
             
          </div>
            
        </section>
        
        <!-- overlay del modal -->
        <div class="overlay-modal hidden-modal"></div>
        <script>
            // obtiene modal por la clase para editar su css
            let modalId = document.querySelector(".modal");
            
            //obtiene el contenido del modal el cual se reemplaza mediante la funcion openModal
            let contentModal = document.getElementById("content-modal");
            
            // obtiene el overlay del modal
            const overlay = document.querySelector(".overlay-modal");
            
            // obtiene el boton con la funcion onclick para abrir el modal
            const openModalBtn = document.querySelector(".btn-open");
            
            // obtiene el boton para cerrar el boton
            const closeModalBtn = document.querySelector(".btn-close");
            
            
             
            // open modal function
            // Esta es la funcion que abre el modal, recibe el parametro id que es el identificador del contenido a abrir
            
            const openModal = function (content) {
            
            // quita el hidden del css del modal y el overlay para que se muestren
              modalId.classList.remove("hidden-modal");
              overlay.classList.remove("hidden-modal");
            
            // se inserta el html en el contenido del modal
              contentModal.innerHTML = content;
            };
            
            // open modal event
            //openModalBtn.addEventListener("click", openModal(modalId));
            
            /*
            // close modal function
            const closeModal = function () {
            
              modalId.classList.add("hidden-modal");
              overlay.classList.add("hidden-modal");
              //contentModal.removeChild()
            };
            
            // cierra el modal cuando se da click al overlay
            closeModalBtn.addEventListener("click", closeModal);
            overlay.addEventListener("click", closeModal);
            
            // close modal when the Esc key is pressed
            document.addEventListener("keydown", function (e) {
              if (e.key === "Escape" && !modalId.classList.contains("hidden-modal")) {
                closeModal();
              }
            });*/
        </script>
        <?php
    }  
    
    //comenzar el proceso
    if (is_page('3843')) {
        
		?>
		
        <style>
            /**************
            *ventana modal*
            ***************/
            
            .container-cards{
                display:flex;
                justify-content:center;
            }
            
            .card-item{
                width:100%;
                max-width:230px;
                margin:10px 15px;
                transition:all .4s;
            }
            
            .card-item:hover{
                transform:scale(1.05);
            }
            
            
            .modal {
              display: block;
              width:100%;
            
              max-width: 900px;
              padding: 1.6rem;
              height:auto;
              position: fixed;
              z-index: 99999999!important;
              top: 20%;
              background-color: #FFFAE6;
              border: 1px solid #ddd;
              border-radius: 25px;
              transition:all .4s;
              top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            }
            
            .modal .flex {
              display: flex;
              align-items: center;
              justify-content: center;
              gap:20px
            }
            
            
            .modal p {
              font-size: 13px;
              color: #000;
              margin: 0.4rem 0 0.2rem;
            }
            
            
            .btn-open {
              cursor:pointer;
            }
            
            .btn-close {
                /* transform: translate(10px, -20px); */
                padding: 0.5rem 0.7rem;
                background: #eee;
                border-radius: 50%;
                /* flex-basis: revert; */
                margin-left: auto;
                z-index:9999999999;
            
            }
            
            .modal-img{
                width:150px;
                text-align: center;
                margin: 0 auto;
                padding: 20px;
            }
            
            .modal-text{
                text-align: center;
            }
            
            .overlay-modal {
              position: fixed;
              top: 0;
              bottom: 0;
              left: 0;
              right: 0;
              width: 100%;
              height: 100%;
              background: rgba(0, 0, 0, 0.5);
              backdrop-filter: blur(6px);
              z-index: 9999;
            }
            
            .hidden-modal {
              display: none;
            }
            
            @keyframes fadeIn {
              0% { opacity: 0; }
              100% { opacity: 1; }
            }
            
            .fade-in-div { animation: fadeIn 0.7s; }
            
            
            @media (max-width:720px){
            
            .container-cards{
                display: table;
                justify-content: center;
            }
            
            
            .card-item {
                width: 50%;
                max-width: auto;
                margin: 0px 15px;
                transition: all .4s;
                display: table-cell;
                padding-left: 10px;
            }
            
            .modal-text h2{
                font-size:20px!important;
            }
            
            .card-item .img-center{
                margin: auto;
                display: block;
                width: 50%;
            }
            
            
            
            }
			#boton-linea-whatsapp{
				background: #004731 !important;
				color: #75FAC6 !important;
				border-radius: 25px !important;
				padding: 10px 33px !important;
				border: 0 !important;
				cursor: pointer;
				text-align: center;
				width: 200px;
				margin: 0 auto;
			}
			#boton-linea-whatsapp:hover{
				background-color: #FFC033!important;
				color: #004731!important;
			}
			#noacepto{
				text-decoration: underline;
				line-height: 3em;
			}

        </style>
        
        <section class="modal hidden-modal fade-in-div">
          <div class="flex">
            <!-- Boton cerrar modal -->
            <button class="btn-close">⨉</button>
          </div>
           <!-- En este div se inserta el contenido por js -->
          <div id="content-modal" class="flex" style="margin-top:-50px;">
             
          </div>
            
        </section>
        
        <!-- overlay del modal -->
        <div class="overlay-modal hidden-modal"></div>
        <script type="text/javascript" id="open_modal_para_comenzar">
			/* VENTANAS MODALES PARA PREREGISTRO */

			// obtiene modal por la clase para editar su css
			let modalId = document.querySelector(".modal");
			
			//obtiene el contenido del modal el cual se reemplaza mediante la funcion openModal
			let contentModal = document.getElementById("content-modal");
			
			// obtiene el overlay del modal
			const overlay = document.querySelector(".overlay-modal");
			
			// obtiene el boton con la funcion onclick para abrir el modal
			const openModalBtn = document.querySelector(".btn-open");
			
			// obtiene el boton para cerrar el boton
			const closeModalBtn = document.querySelector(".btn-close");
			
			
			// estas variables html + numero contienen el html dinamico para un contenido de modal especifico
			
			let alerta_protecciondatos = "<div class='modal-text'><h2 style='color:#0ABF76'>Políticas de Privacidad</h2> <p>Lorem ipsum dolor sit amet consectetur. In ultrices interdum nunc elit. Habitasse tellus elit magna netus ac neque sagittis sed enim. Pretium lectus diam rutrum lectus malesuada id pellentesque molestie nunc. Nisi eu lacus fermentum venenatis est morbi tellus placerat ac. Interdum scelerisque ultricies amet a tempor. Arcu lacus in donec pellentesque egestas orci facilisis eleifend volutpat. Sapien aliquet maecenas lectus pharetra pellentesque morbi montes nunc. Eu elementum sed ipsum orci ut vel. Mauris elit libero at consectetur.</p><br><center><a id='boton-linea-whatsapp' href='/agendar-consulta-medica-integral/'>Continuar</a><br/><p><a id='noacepto' href='/quiero-comenzar-el-proceso-de-aborto-ya/' >No estoy de acuerdo</a></p></center></div>";
			let alerta_noacepto = "<div class='modal-text'><h2 style='color:#0ABF76'>Políticas de Privacidad</h2> <p>Lo sentimos no podrás continuar con tu registro</p></div>";
     
			// open modal function
			// Esta es la funcion que abre el modal, recibe el parametro id que es el identificador del contenido a abrir
			
			const openModal = function (id) {
			
			// variable que inserta el contenido html en el contenido del modal de acuerdo al parametro de modal ingresado
			let content  = "";
			
			// validacion del parametro id para asignar el contenido html a la variable contenido
			switch (id) {
			  case 'alerta-protecciondatos':
				content = alerta_protecciondatos;
				break;
			  case 'alerta-noacepto':
				content = alerta_noacepto;
				break;
				
			}
			
			// quita el hidden del css del modal y el overlay para que se muestren
			  modalId.classList.remove("hidden-modal");
			  overlay.classList.remove("hidden-modal");
			
			// se inserta el html en el contenido del modal
			  contentModal.innerHTML = content;
			};
			
			// open modal event
			//openModalBtn.addEventListener("click", openModal(modalId));
			
			
			// close modal function
			const closeModal = function () {
			
			  modalId.classList.add("hidden-modal");
			  overlay.classList.add("hidden-modal");
			  //contentModal.removeChild()
			};
			
			// cierra el modal cuando se da click al overlay
			closeModalBtn.addEventListener("click", closeModal);
			overlay.addEventListener("click", closeModal);
			
			// close modal when the Esc key is pressed
			document.addEventListener("keydown", function (e) {
			  if (e.key === "Escape" && !modalId.classList.contains("hidden-modal")) {
				closeModal();
			  }
			});
			
			//*COMPORTAMIENTO DEL BOTON PARA EL MODAL*///
			jQuery(document).ready(function($) {
				
				$('#comenzar-proceso a.single-media-link').on("click", function() {
					openModal('alerta-protecciondatos');
				});
				
				$('#noacepto').on("click", function() {
					closeModal();
					//openModal('alerta-noacepto');
				});
				
			});
		</script>
		<?php
    }
}

// Hook para ejecutar la función en el momento adecuado
add_action('wp_footer', 'validar_parametros_url');

// Añadir acción para redirigir al home si la orden está fallida
add_action('woocommerce_thankyou', 'redirect_on_failed_order');

function redirect_on_failed_order($order_id) {
    // Obtener la instancia de la orden
    $order = wc_get_order($order_id);

    // Verificar si la orden está marcada como "fallida"
    if ($order && $order->get_status() === 'failed') {
        // Redirigir al home de WordPress
        wp_redirect(home_url());
        exit;
    }
}