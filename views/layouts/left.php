<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">                
                <img src="<?= $directoryAsset ?>/img/User.png" class="img-circle" alt="Imagen "/>
            </div>
            <div class="pull-left info">
                <p><?php echo (!\Yii::$app->user->isGuest) ? Yii::$app->user->identity->nikname : ''; ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>		
        <!-- search form 
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?=
        dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu','data-widget'=> 'tree'],
                    'items' => [
                        ['label' => 'Disponibles', 'icon' => 'plus-square', 
									'url' => ['/a2-noticias/inmuebles-disponibles']],                     
                        ['label' => 'Clientes', 'icon' => 'users', 'url' => ['/a2-clientes'],
							'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false],
                        ['label' => 'Inmuebles', 'icon' => 'building', 'url' => '#',  'items' => [
                                ['label' => 'Inmuebles', 'icon' => 'building-o', 'url' => ['/a2-noticias'],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                ['label' => 'Por vencer', 'icon' => 'clock-o', 
									'url' => ['/a2-noticias/inmuebles-vencer']],
                                /*['label' => 'Expensas', 'icon' => 'building-o', 'url' => ['/a2-noticias/expensas'],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                            Yii::$app->user->identity->permisos=='intermedio')?true:false,],*/
                            ['label' => 'Expensas', 'icon' => 'building-o', 'url' => '#','items'=>[
                                ['label' => 'Expensas', 'icon' => '', 'url' => ['/a2-noticias/expensas'],],
                                ['label' => 'Aprobar Expensas', 'icon' => '', 'url' => ['/a2-noticias/aprobar-expensas'],],
                                ['label' => 'Impresión', 'icon' => 'building-o', 'url' => '#','items'=>[
                                    ['label' => 'Consorcio', 'icon' => '', 'url' => ['/informes/expensas-consorcio-excel'],],
                                    ['label' => 'Inmueble', 'icon' => '', 'url' => ['/informes/expensas-cargadas-excel',"inmueble"=>1],],
                                ]],
                                
                            ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                            Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                            ['label' => 'Expensas Grupo', 'icon' => 'building-o', 'url' => '#','items'=>[
                                ['label' => 'Expensas por Grupo', 'icon' => '', 'url' => ['/grupo-expensas/index'],],
                                ['label' => 'Expensas Multiples', 'icon' => '', 'url' => ['/grupo-expensas/create-multiple'],],
                                ['label' => 'Expensas por Rubro', 'icon' => '', 'url' => ['/grupo-expensas/create-by-rubro'],],
                            ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                            Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                
                                 ['label' => 'Morosos', 'icon' => '', 
                                    'url' => ['/a2-liquidaciones/morosos']],
                            ],],
                        ['label' => 'Cobros', 'icon' => 'list-alt', 'url' => '#', 'items' => [
                                ['label' => 'Alquileres por Cobrar', 'icon' => '', 'url' => ['/a2-liquidaciones'],],
                                ['label' => 'Alquileres Pagados', 'icon' => '', 'url' => ['/a2-liquidaciones/liquidaciones-pagadas'],'visible'=>(Yii::$app->user->identity->permisos=='administrador'							)?true:false,],
								//['label' => 'Reimprimir Comprobantes', 'icon' => '', 'url' => ['/a2-liquidaciones/listado-reimprimir-comprobante'],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							//Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                //['label' => 'Expensas por Cobrar', 'icon' => '', 'url' => ['/a2-liquidaciones/expensas-deuda'],],
                                //['label' => 'Expensas Pagadas', 'icon' => '', 'url' => ['/a2-liquidaciones/expensas-pagadas'],
                                //    'visible'=>(Yii::$app->user->identity->permisos=='administrador')?true:false,],
                            ],],
                        ['label' => 'Cajas', 'icon' => 'usd', 'url' => '#', 'items' => [
                                ['label' => 'Caja Arqueo', 'icon' => '', 'url' => ['/a2-caja/arqueo-caja'],],
                                ['label' => 'Resumen de Cajas', 'icon' => '', 'items' => [
                                    ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/resumen-cajas',],
                                        'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/resumen-cajas-excel'],
                                        'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Nota de Crédito', 'icon' => '', 'url' => ['/nota-credito'],],
                                        ['label' => 'Cierre X', 'icon' => '', 'url' => ['/nota-credito/cierre-x'],],
                                        ['label' => 'Cierre Z', 'icon' => '', 'url' => ['/nota-credito/cierre-z'],],
                                ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                            ],],
                        ['label' => 'Pagos', 'icon' => 'exchange', 'url' => '#', 'items' => [
                                ['label' => 'Preparar Liq. a Propietario', 'icon' => '', 'url' => ['/liquidacionpagadas/index'],],
                                ['label' => 'Pagar a propietario', 'icon' => '', 'url' => ['/liquidacionpagadas/para-pagar'],],
                                ['label' => 'Pagos Efectuados', 'icon' => '', 'url' => ['/liquidacionpagadas/pagadas'],],
                            ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                        ['label' => 'Configuracion', 'icon' => 'cogs', 'url' => '#', 'items' => [
                                ['label' => 'Grupos', 'icon' => '', 'url' => ['/a2-grupos'],],
                                ['label' => 'Inmobiliarias', 'icon' => '', 'url' => ['/a2-inmobiliarias'],],
                                ['label' => 'Propietarios', 'icon' => '', 'url' => ['/propietarios'],],
                                ['label' => 'Tipos de Expensas', 'icon' => '', 'url' => ['/tipo-expensas'],],
                                ['label' => 'Plantillas de Documentos', 'icon' => '', 'url' => ['/plantillas'],],
                                ['label' => 'Usuarios', 'icon' => '', 'url' => ['/a-noticias-usuarios'],],
                                ['label' => 'Impresora y otros', 'icon' => '', 'url' => ['/a2-parametros-globales/update','id'=>1],],
                                
                                ['label' => 'Auditoria de Contratos', 'icon' => '', 'url' => ['/auditoria-contratos'],],
                            ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                        ['label' => 'Informes', 'icon' => 'file-text-o', 'url' => '#', 'items' => [
                                ['label' => 'Comprobantes Cobrados', 'icon' => '', 
									'url' => ['/a2-liquidaciones/listado-reimprimir-comprobante'],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                ['label' => 'Informe de Clientes', 'icon' => '', 'url' => '#', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/listado-clientes',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/listado-clientes-excel'],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ],],
                                ['label' => 'Padrones', 'icon' => '', 'url' => '#', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/padrones',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/padrones-excel'],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                ['label' => 'Listado Precio Locaciones', 'icon' => '', 'url' => '#', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/precio-locaciones',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/precio-locaciones-excel'],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                            Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                ['label' => 'Listado Morosos', 'icon' => '', 'url' => '#', 'items' => [
                                    ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/morosos',],
                                        'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/a2-liquidaciones/morosos-liq-expensas'],
                                        'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                ],],
                                /*['label' => 'Listado Morosos', 'icon' => '', 'url' => '#', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/morosos',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/morosos-excel'],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ],],
                                ['label' => 'Listado Morosos Expensas', 'icon' => '', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/morosos-expensas',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/morosos-expensas-excel'],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                    ],],*/
                                
                                ['label' => 'Informe de Libro Ventas', 'icon' => '', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/libro-ventas',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                                ['label' => 'Intereses por Mora', 'icon' => '', 'items' => [
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/intereses-mora',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        
                                        
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],                              
                                
                                ['label' => 'Expensas Cargadas', 'icon' => '', 'items' => [
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/expensas-cargadas-excel',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'], 
                                        ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/expensas-cargadas',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],                               
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                            Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                            ['label' => 'Gestión de Cobranzas', 'icon' => '', 'items' => [
                                ['label' => 'PDF', 'icon' => 'file-pdf-o', 'url' => ['/informes/gestion-cobranzas',],
                                    'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                
                            ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
                    Yii::$app->user->identity->permisos=='intermedio')?true:false,],
							['label' => 'Auditoria por propietarios', 'icon' => '', 'items' => [
                                        ['label' => 'Excel', 'icon' => 'file-excel-o', 'url' => ['/informes/auditoria-por-propietario-excel',],
                                            'template' => '<a href="{url}" target="_blank">{icon} {label}</a>'],
                                        
                                    ],'visible'=>(Yii::$app->user->identity->permisos=='administrador' || 
							Yii::$app->user->identity->permisos=='intermedio')?true:false,],
                            ],],
                    
                    ],
                ]
        )
        ?>

    </section>

</aside>
