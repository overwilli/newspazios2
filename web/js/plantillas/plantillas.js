$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('plantillas-texto',{on: {
        pluginsLoaded: function() {
            var editor = this,
                config = editor.config;

            editor.ui.addRichCombo( 'my-combo', {
                label: 'Etiquetas contrato',
                title: 'Etiquetas contrato',
                toolbar: 'basicstyles,0',

                panel: {               
                    css: [ CKEDITOR.skin.getPath( 'editor' ) ].concat( config.contentsCss ),
                    multiSelect: false,
                    attributes: { 'aria-label': 'Etiquetas' }
                },

                init: function() {    
                    this.startGroup( 'Datos Contrato' );
                    this.add( '@dia_contrato', 'D&iacute;a Contrato' );
                    this.add( '@mes_desde', 'Mes desde' ); 
                    this.add( '@anio_desde', 'A&ntilde;o desde' );
                    this.add( '@mes_hasta', 'Mes hasta' ); 
                    this.add( '@anio_hasta', 'A&ntilde;o hasta' );
                    this.add( '@meses_contrato', 'Cantidad de Meses' );
                    this.add( '@meses_letras_contrato', 'Cantidad de Meses en Letras' );
                    this.add( '@destino_contrato', 'Destino Contrato' );
                    this.add( '@deposito_garantia_contrato', 'Deposito Garantia' );
                    this.add( '@dia_letras_contrato', 'Día en Letras de Fecha Contrato' ); 
                    this.add( '@mes_letras_contrato', 'Mes en Letras de Fecha Contrato' );        
                    this.add( '@anio_letras_contrato', 'Año en Letras de Fecha Contrato' ); 
                    this.add( '@periodo_importe1', 'Primer Semestre  e Importe' );
                    this.add( '@periodo_importe2', 'Segundo Semestre  e Importe' );
                    this.add( '@periodo_importe3', 'Tercer Semestre  e Importe' ); 

                    this.startGroup( 'Datos del Inmueble' );
                    this.add( '@direccion_inmueble', 'Direccion' );
                    this.add( '@localidad_inmueble', 'Localidad' );
                    this.add( '@provincia_inmueble', 'Provincia' );                   

                    this.startGroup( 'Locador' );
                    this.add( '@apellido_nombre_locador', 'Apellido y Nombre' );                                       
                    this.add( '@dni_locador', 'DNI' ); 
                    this.add( '@direccion_locador', 'Direccion' ); 
                    this.add( '@telefono_locador', 'Telefono' );                    
                    this.add( '@provincia_locador', 'Provincia' );

                    this.startGroup( 'Datos Cliente' );
                    this.add( '@apellido_nombre_cliente', 'Apellido y Nombre' );                                       
                    this.add( '@dni_cliente', 'DNI' ); 
                    this.add( '@direccion_cliente', 'Direccion' ); 
                    this.add( '@telefono_cliente', 'Telefono' );
                    this.add( '@barrio_cliente', 'Barrio' );
                    
                    this.startGroup( 'Inquilino 1' );
                    this.add( 'apellido_nombre_inquilino_1', 'Apellido y Nombre' );                             
                    this.add( '@dni_inquilino_1', 'DNI' ); 
                    this.add( '@telefono_inquilino_1', 'Telefono' );
                    
                    this.startGroup( 'Inquilino 2' );
                    this.add( 'apellido_nombre_inquilino_2', 'Apellido y Nombre' );                             
                    this.add( '@dni_inquilino_2', 'DNI' ); 
                    this.add( '@telefono_inquilino_2', 'Telefono' );
                    
                    this.startGroup( 'Garante' );
                    this.add( 'apellido_nombre_garante', 'Apellido y Nombre' );                             
                    this.add( '@dni_garante', 'DNI' ); 
                    this.add( '@telefono_garante', 'Telefono' );

                },

                onClick: function( value ) {
                    editor.focus();
                    editor.fire( 'saveSnapshot' );

                    editor.insertHtml( value );

                    editor.fire( 'saveSnapshot' );
                }
            } );        
        }        
    }});
    //bootstrap WYSIHTML5 - text editor
    //$(".textarea").wysihtml5();
  });