<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Punto de Venta - POS</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Template CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    @vite(['public/assets/scss/style.scss'])
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <style>
        :root {
            --pos-primary: #007bff; /* Azul estandar o ajustado a la imagen */
            --pos-secondary: #6c757d;
            --pos-bg: #f5f7fb;
            --pos-header-height: 70px;
            --pos-cart-width: 450px;
            --pos-accent: #ffc107;
            --pos-orange: #fd7e14;
            --pos-primary: #007bff;
            --pos-secondary: #6c757d;
            --pos-bg: #f8f9fc;
            --pos-header-height: 70px;
            --pos-cart-width: 450px;
            --pos-accent: #ffc107;
            --pos-orange: #fd7e14;
            --pos-text-dark: #2d3436;
        }

        body { overflow: hidden; background-color: var(--pos-bg); font-family: 'Rubik', sans-serif; }
        
        /* Layout General */
        .pos-container { display: flex; flex-direction: column; height: 100vh; }
        
        /* Header */
        .pos-header {
            height: var(--pos-header-height);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 100;
        }
        .header-tools .btn {
            border-radius: 4px;
            margin: 0 2px;
            width: 40px;
            height: 40px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #fff;
            background-color: #0dafef; /* Azul claro estilo imagen */
            border: none;
            transition: all 0.2s;
        }
        .header-tools .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .header-tools .btn.btn-orange { background-color: var(--pos-orange); }

        /* Cuerpo principal */
        .pos-body { display: flex; flex: 1; overflow: hidden; margin-top: 15px; }
        .pos-products-section { flex: 1; display: flex; flex-direction: column; overflow: hidden; padding: 0 20px 20px 20px; }
        .pos-cart-section { width: var(--pos-cart-width); display: flex; flex-direction: column; background: #fff; border-left: 1px solid #e9ecef; margin-right: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05); overflow: hidden; }

        /* Scroll Oculto */
        .categories-scroll-container::-webkit-scrollbar { display: none; }
        
        /* Scroll Oculto */
        .categories-scroll-container::-webkit-scrollbar { display: none; }
        
        /* Buscador Nuevo Diseño */
        .search-container { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .search-input-group { flex: 1; position: relative; }
        .search-input-group input { padding-left: 40px; border-radius: 20px; border: 1px solid #e9ecef; background: #f8f9fa; height: 45px; }
        .search-input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        
        .categories-scroll { overflow-x: auto; white-space: nowrap; padding-bottom: 10px; margin-bottom: 10px; -webkit-overflow-scrolling: touch; }
        .categories-scroll::-webkit-scrollbar { height: 4px; }
        .categories-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .category-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: #eef5f9;
            color: #444;
            border-radius: 50px;
            white-space: nowrap;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .category-pill:hover { background: #e2e6ea; }
        .category-pill.active { background: #d7ecff; color: var(--pos-primary); border-color: var(--pos-primary); }
        
        /* Grid Productos */
        .products-scroll { flex: 1; overflow-y: auto; padding-right: 5px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; padding-bottom: 20px; }
        
        /* Tarjeta Producto */
        .product-card {
            background: #fff;
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer; height: 100%; border: 2px solid transparent;
            display: flex; flex-direction: column;
        }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); border-color: var(--pos-primary); }
        
        /* Product Header (Badges) */
        .product-header {
            padding: 6px 8px; /* Reducido */
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: #fff;
        }

        .product-price-badge {
            background: var(--pos-primary); color: white;
            padding: 3px 8px; border-radius: 6px;
            font-weight: 700; font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(13,110,253,0.3);
        }
        
        .product-info-badge {
            background: #eef5f9; color: var(--pos-secondary);
            padding: 3px 6px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 600;
            border: 1px solid #dee2e6;
        }

        .product-img-container {
            height: 110px; /* Reducido de 140px */
            width: 100%;
            display: flex; align-items: center; justify-content: center;
            background: #fff; padding: 5px; /* Reducido */
        }
        .product-img-container img { max-height: 100%; max-width: 100%; object-fit: contain; }
        .no-image-icon { font-size: 4rem; color: #dee2e6; }
        
        .product-details { padding: 4px 8px; text-align: center; background: #fff; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        .product-name { font-weight: 600; color: #333; font-size: 0.85rem; line-height: 1.2; margin-bottom: 2px; }
        .product-code { font-size: 0.7rem; color: #adb5bd; font-family: monospace; letter-spacing: 0.5px; }

        /* Carrito */
        .cart-header { padding: 20px; border-bottom: 1px solid #e9ecef; }
        .cart-header h5 { margin: 0; font-weight: 700; color: #333; }
        .cart-subheader { background: #0dafef; color: white; padding: 10px 15px; font-weight: 600; text-align: center; font-size: 0.9rem; }
        
        .cart-items-container { flex: 1; overflow-y: auto; background: #f8f9fa; padding: 10px; }
        
        .cart-item {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .item-info { flex: 1; padding-right: 10px; }
        .item-name { font-weight: 600; font-size: 0.85rem; color: #333; margin-bottom: 4px; line-height: 1.2; }
        .item-price { color: #555; font-size: 0.9rem; }
        
        .item-controls { display: flex; align-items: center; gap: 8px; }
        .btn-qty {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-minus { background: #ffc107; color: #333; }
        .btn-plus { background: #ffc107; color: #333; }
        .item-qty-input { width: 30px; text-align: center; border: none; font-weight: bold; background: transparent; }
        
        .btn-remove-item {
            color: #dc3545;
            background: none;
            border: none;
            padding: 5px;
            margin-top: 5px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .cart-footer { background: #fff; padding: 20px; border-top: 1px solid #e9ecef; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95rem; }
        .total-row { display: flex; justify-content: space-between; margin-top: 15px; font-size: 1.2rem; font-weight: bold; color: var(--pos-primary); padding-top: 10px; border-top: 1px dashed #e9ecef; }
        
        .btn-checkout {
            width: 100%;
            background: #0088cc;
            color: white;
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 4px; /* Cuadrado como en la imagen */
            margin-top: 15px;
            transition: all 0.2s;
        }
        .btn-checkout:hover { background: #0077b3; }
        .btn-checkout:disabled { background: #ccc; cursor: not-allowed; }

    </style>
</head>

<body class="validvia-pos">
    
    <!-- POS Container -->
    <div class="pos-container">
        
        <!-- Header Superior -->
        <header class="pos-header">
            <div class="d-flex align-items-center">
                <!-- Branding/Logo -->
                <div class="me-4">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 40px;">
                </div>
            </div>

            <!-- Botones de Acción Globales (Mockup visual) -->
            <div class="header-tools d-none d-md-flex">
                <button class="btn" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i></button>
                <button class="btn" title="Inicio" onclick="window.location.href='{{ route('dashboard') }}'"><i class="fa fa-home"></i></button>
                <button class="btn" title="Calculadora"><i class="fa fa-calculator"></i></button>
                <button class="btn" title="Etiquetas"><i class="fa fa-tag"></i></button>
                <button class="btn btn-orange" title="Devolución"><i class="fa fa-reply"></i></button>
                <button class="btn btn-orange" title="Inventario"><i class="fa fa-archive"></i></button>
                <button class="btn" title="Ventas"><i class="fa fa-shopping-cart"></i></button>
            </div>

            <!-- Usuario Info -->
            <div class="d-flex align-items-center gap-2">
                <div class="text-end lh-1 d-none d-sm-block">
                    <span class="d-block fw-bold" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <small class="text-muted">EMPRESA</small>
                </div>
                <img src="{{ asset('assets/images/user/1.jpg') }}" class="rounded-circle" width="40" height="40" alt="Avatar" onerror="this.src='{{ asset('assets/images/dashboard/1.png') }}'">
            </div>
        </header>

        <!-- Contenido Principal -->
        <div class="pos-body">
            
            <!-- SECCIÓN IZQUIERDA: PRODUCTOS -->
            <div class="pos-products-section">
                
                <!-- Buscador y Filtros (Nuevo Diseño) -->
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body p-2">
                        <!-- Fila Superior: Buscadores -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <!-- Buscador Texto (Siempre visible, iconos a la derecha) -->
                            <div class="position-relative flex-grow-1" id="searchContainer">
                                <input type="text" id="searchInput" class="form-control rounded-pill pe-5" placeholder="Filtro por código o nombre" autocomplete="off" style="padding-left: 20px;">
                                <div class="position-absolute top-50 end-0 translate-middle-y me-3 d-flex align-items-center gap-2">
                                    <i class="fa fa-search text-muted"></i>
                                    <i class="fa fa-times text-muted cursor-pointer" id="clearSearchBtn" onclick="clearSearchInput()" style="display: none;"></i>
                                </div>
                            </div>

                            <!-- Switch Scanner -->
                            <div class="form-check form-switch custom-switch d-flex align-items-center mb-0" title="Activar Lector de Código de Barras">
                                <input class="form-check-input cursor-pointer" type="checkbox" id="scannerMode" style="width: 3rem; height: 1.5rem;">
                            </div>

                            <!-- Buscador Código de Barras (Visible solo si Switch ON) -->
                            <!-- Usamos visibility para mantener el espacio reservado y evitar saltos de tamaño -->
                            <div class="position-relative flex-grow-1" id="barcodeContainer" style="{{ request()->cookie('scanner_active') ? '' : 'visibility: hidden;' }}">
                                <input type="text" id="barcodeInput" class="form-control rounded-pill" placeholder="Escanea código de barras..." disabled>
                                <i class="fa fa-barcode position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                            </div>

                            <!-- Botón Ver Todos -->
                            <button class="btn btn-link text-decoration-none text-muted fw-bold text-nowrap" onclick="resetAllFilters()">Ver Todos</button>
                        </div>

                        <!-- Fila Inferior: Categorías Carrusel -->
                        <div class="d-flex align-items-center gap-2">
                            <!-- Flecha Izquierda -->
                            <button class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center flex-shrink-0" id="scrollLeft" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            
                            <div class="categories-scroll-container d-flex gap-2 align-items-center flex-grow-1" id="categoryContainer" style="overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none;">
                                <div class="category-pill active" data-category="">
                                    <i class="fa fa-th-large bg-primary text-white p-1 rounded-circle me-1" style="font-size: 0.7rem;"></i> TODO
                                </div>
                                @foreach($categories as $category)
                                <div class="category-pill" data-category="{{ $category->id }}">
                                    <i class="fa fa-circle text-info p-1 rounded-circle me-1" style="font-size: 0.5rem;"></i> {{ $category->name }}
                                </div>
                                @endforeach
                            </div>

                            <!-- Flecha Derecha -->
                            <button class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center flex-shrink-0" id="scrollRight" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid de Productos -->
                <div class="products-scroll">
                    <div class="product-grid" id="productGrid">
                        @forelse($products as $product)
                        <div class="product-item-wrapper" 
                             data-id="{{ $product->id }}"
                             data-name="{{ strtolower($product->name) }}"
                             data-code="{{ strtolower($product->code ?? '') }}"
                             data-stock="{{ $product->stocks->sum('quantity') ?? 0 }}"
                             data-category="{{ $product->product_category_id }}">
                            
                            <div class="product-card" onclick="addToCart({{ $product->id }})">
                                <!-- Product Header (Price & Stock) -->
                                <div class="product-header">
                                    <div class="product-price-badge">
                                        S/ {{ number_format($product->sale_price ?? 0, 2) }}
                                    </div>
                                    <div class="product-info-badge" title="Stock Disponible">
                                        {{ $product->stocks->sum('quantity') ?? 0 }} {{ $product->unit->symbol ?? 'UNI' }}
                                    </div>
                                </div>

                                <!-- Imagen -->
                                <div class="product-img-container">
                                    @if($product->image_path)
                                    <img src="{{ Str::startsWith($product->image_path, 'storage/') ? asset($product->image_path) : asset('storage/' . $product->image_path) }}?v={{ time() }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="no-image-icon" style="display: none;">
                                        <i class="fa fa-cube"></i>
                                    </div>
                                    @else
                                    <div class="no-image-icon">
                                        <i class="fa fa-cube"></i>
                                    </div>
                                    @endif
                                </div>

                                <!-- Detalle -->
                                <div class="product-details">
                                    <div class="product-name text-truncate-2">{{ $product->name }}</div>
                                    <div class="product-code">{{ $product->code ?? 'SIN CÓDIGO' }}</div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay productos disponibles</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- SECCIÓN DERECHA: CARRITO -->
            <div class="pos-cart-section">
                
                <div class="cart-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold">Nuevo Pedido</h5>
                    </div>
                    <small class="text-muted">{{ now()->format('l, d de F de Y, H:i') }}</small>
                </div>

                <!-- Cliente Selector -->
                <div class="px-3 py-3 bg-white border-bottom d-flex justify-content-between align-items-center cursor-pointer hover-bg-light transition-base" onclick="openCustomerModal()" style="border-left: 4px solid var(--pos-primary);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fa fa-user text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-bold mb-0" style="font-size: 0.7rem; letter-spacing: 1px;">CLIENTE</div>
                            <div class="fw-bolder text-dark fs-6" id="customerName" style="color: #000 !important;">{{ $genericCustomer?->name ?? 'CLIENTE GENÉRICO' }}</div>
                            <div class="text-muted small" style="font-size:0.8rem" id="customerTaxId">{{ $genericCustomer?->tax_id ?? '00000000' }}</div>
                        </div>
                    </div>
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                        <i class="fa fa-chevron-right text-muted small"></i>
                    </div>
                </div>
                <input type="hidden" id="selectedCustomerId" value="{{ $genericCustomer?->id ?? '' }}">


                <div class="cart-subheader">
                    Datos del Pedido
                </div>

                <!-- Lista de Items -->
                <div class="cart-items-container" id="cartItems">
                    <!-- Items inyectados por JS -->
                    <div class="text-center py-5 text-muted opacity-50">
                        <i class="fa fa-shopping-basket fa-3x mb-2"></i>
                        <p>Carrito Vacío</p>
                    </div>
                </div>

                <!-- Footer Totales -->
                <div class="cart-footer">
                    <div class="summary-row">
                        <span>Sub Total</span>
                        <span class="fw-bold" id="subtotalDisplay">S/ 0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>I.G.V 18%</span>
                        <span class="fw-bold" id="taxDisplay">S/ 0.00</span>
                    </div>
                    
                    <div class="total-row">
                        <span>Total</span>
                        <span id="totalDisplay">S/ 0.00</span>
                    </div>

                    <button class="btn-checkout" id="btnPay" onclick="openPaymentModal()" disabled>
                        Ir a caja
                    </button>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal de Pago (Reutilizado funcionalidad existente, estilo ajustado) -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="fa fa-credit-card me-2"></i>Finalizar Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de Pago (Misma lógica) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">TIPO DE DOCUMENTO</label>
                        <select class="form-select" id="documentType">
                            @foreach($series as $s)
                            <option value="{{ $s->id }}">{{ $s->prefix }} - {{ $s->documentType?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">MÉTODO DE PAGO</label>
                        <div class="d-grid gap-2" style="grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));">
                            @foreach($paymentMethods as $pm)
                            <button class="btn btn-outline-primary payment-method-btn p-3 {{ $loop->first ? 'active' : '' }}" 
                                    data-id="{{ $pm->id }}" onclick="selectPaymentMethod(this)">
                                <i class="fa fa-{{ $pm->icon ?? 'money' }} mb-2 d-block fs-4"></i>
                                <span class="small fw-bold">{{ $pm->name }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 fw-bold">Total a Pagar:</h4>
                        <h3 class="mb-0 fw-bold text-primary" id="modalTotal">S/ 0.00</h3>
                    </div>

                    <div class="row g-2 align-items-center">
                        <div class="col-auto"><label class="col-form-label fw-bold">Recibido:</label></div>
                        <div class="col">
                            <input type="number" class="form-control form-control-lg text-end fw-bold" id="amountReceived" placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3 py-2 text-center" id="changeDisplay" style="display: none;">
                        Vuelto: <span class="fw-bold fs-5" id="changeAmount">S/ 0.00</span>
                    </div>

                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4 fw-bold" onclick="processSale()" id="btnConfirmPay">
                        CONFIRMAR COMPRA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cliente (Reutilizado funcionalidad) -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" id="customerSearch" placeholder="Buscar por Nombre o RUC/DNI...">
                    <div id="customerResults" class="list-group mb-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Helper para Alertas Seguras (Fallback si no carga SweetAlert)
        function showPosAlert(icon, title, text, timer = 2000) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    timer: timer,
                    showConfirmButton: false
                });
            } else {
                alert(title + ': ' + text);
            }
        }

        function confirmPosAction(title, text, confirmText) {
            if (typeof Swal !== 'undefined') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancelar'
                }).then(result => result.isConfirmed);
            } else {
                return Promise.resolve(confirm(text));
            }
        }

        // Lógica de Negocio POS (Adaptada al nuevo UI)
        let cart = [];
        let selectedCustomerId = document.getElementById('selectedCustomerId').value;
        let selectedPaymentMethodId = 1;

        function formatCurrency(amount) {
            return 'S/ ' + parseFloat(amount).toFixed(2);
        }

        function addToCart(productId) {
            const itemEl = document.querySelector(`.product-item-wrapper[data-id="${productId}"]`);
            if (!itemEl) return;
            
            const stock = parseFloat(itemEl.dataset.stock) || 0;

            // Validación Stock 0
            if (stock <= 0) {
                showPosAlert('error', 'Sin Stock', 'No hay stock disponible para este producto.');
                return;
            }

            // Animación simple de click 
            const card = itemEl.querySelector('.product-card');
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.style.transform = '', 100);

            const existing = cart.find(c => c.product_id === productId);
            if (existing) {
                // Validación Exceso de Stock
                if (existing.quantity + 1 > stock) {
                    showPosAlert('warning', 'Límite Alcanzado', `Solo tienes ${stock} unidades disponibles.`);
                    return;
                }
                existing.quantity++;
            } else {
                cart.push({
                    product_id: productId,
                    name: itemEl.dataset.name.toUpperCase(),
                    unit_price: parseFloat(itemEl.querySelector('.product-price-badge').innerText.replace('S/ ', '')) || 0,
                    quantity: 1,
                    max_stock: stock 
                });
            }
            renderCart();
        }

        function updateQty(productId, delta) {
            const item = cart.find(c => c.product_id === productId);
            if (!item) return;
            
            const newQty = item.quantity + delta;
            
            // Validación Stock al subir cantidad manualmente
            if (delta > 0 && newQty > item.max_stock) {
                 showPosAlert('warning', 'Límite de Stock', `Stock máximo disponible: ${item.max_stock}`, 1500);
                 return;
            }

            if (newQty <= 0) {
                confirmPosAction('¿Eliminar producto?', 'Saldrá de la lista de pedido', 'Sí, eliminar')
                .then((isConfirmed) => {
                    if (isConfirmed) {
                        cart = cart.filter(c => c.product_id !== productId);
                        renderCart();
                    }
                });
                return; // Importante retornar para evitar asignar negativo temporalmente
            } else {
                item.quantity = newQty;
            }
            renderCart();
        }

        function removeItem(productId) {
            cart = cart.filter(c => c.product_id !== productId);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 text-muted opacity-50">
                        <i class="fa fa-shopping-basket fa-3x mb-2"></i>
                        <p>Carrito Vacío</p>
                    </div>`;
                updateTotals(0);
                return;
            }

            let html = '';
            let subtotal = 0;
            
            cart.forEach(item => {
                const totalItem = item.quantity * item.unit_price;
                subtotal += totalItem;
                
                html += `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-price">${formatCurrency(item.unit_price)}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <div class="item-controls">
                            <button class="btn-qty btn-minus" onclick="updateQty(${item.product_id}, -1)">-</button>
                            <span class="item-qty-input">${item.quantity}</span>
                            <button class="btn-qty btn-plus" onclick="updateQty(${item.product_id}, 1)">+</button>
                        </div>
                        <i class="fa fa-trash text-danger cursor-pointer" style="font-size: 0.9rem;" onclick="removeItem(${item.product_id})"></i>
                    </div>
                </div>`;
            });
            
            container.innerHTML = html;
            updateTotals(subtotal);
            
            // Auto scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        function updateTotals(subtotal) {
            const tax = subtotal * 0.18;
            const total = subtotal; // Asumiendo precios incluyen IGV para simplificar visualización, o ajustar lógica segun negocio
            // Si precios NO incluyen IGV: const total = subtotal + tax;
            
            // Segun imagen, parece calcularse directo. Ajustaré para que el total sea la suma y desglose el IGV si es necesario.
            // Para este template, mostrare Subtotal e IGV informativos, Total = suma items.
            
             // Ajuste logica peru: Generalmente precios incluyen IGV en retail.
             // Base imponible = Total / 1.18
             const base = total / 1.18;
             const igv_amount = total - base;

            document.getElementById('subtotalDisplay').textContent = formatCurrency(base);
            document.getElementById('taxDisplay').textContent = formatCurrency(igv_amount);
            document.getElementById('totalDisplay').textContent = formatCurrency(total);
            document.getElementById('btnPay').disabled = total <= 0;
            
            // Modal updates
            document.getElementById('modalTotal').textContent = formatCurrency(total);
            document.getElementById('amountReceived').value = total.toFixed(2);
        }

        // ========== BÚSQUEDA Y SCANNER (NUEVO) ==========
        const searchInput = document.getElementById('searchInput');
        const barcodeInput = document.getElementById('barcodeInput');
        const searchContainer = document.getElementById('searchContainer');
        const barcodeContainer = document.getElementById('barcodeContainer');
        const scannerMode = document.getElementById('scannerMode');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const categoryContainer = document.getElementById('categoryContainer');
        let searchTimeout;

        // 1. Toggle Mode Scanner
        scannerMode.addEventListener('change', function() {
            if(this.checked) {
                // Modo Scanner Activo
                // searchContainer.style.display = 'block'; 
                barcodeContainer.style.visibility = 'visible'; // Mostrar visualmente
                
                barcodeInput.disabled = false;
                barcodeInput.focus();
                
                // Si el usuario quiere borrar el texto al activar scanner, descomentar:
                // searchInput.value = '';
                // filterProducts();
                
                document.cookie = "scanner_active=1; path=/";
            } else {
                // Modo Busqueda Manual
                barcodeContainer.style.visibility = 'hidden'; // Ocultar visualmente pero mantener espacio
                // searchContainer.style.display = 'block'; // Siempre visible

                barcodeInput.disabled = true;
                barcodeInput.value = '';
                
                searchInput.disabled = false;
                searchInput.focus();

                document.cookie = "scanner_active=0; path=/";
            }
        });

        // 2. Buscador Texto (Manual)
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            // Mostrar botón X solo si hay texto en el input de Texto
            clearSearchBtn.style.display = q.length > 0 ? 'flex' : 'none'; 
            
            filterProducts(); // Sin delay para feedback instantáneo si es posible, o bajo delay
        });

        function clearSearchInput() {
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            filterProducts();
            searchInput.focus();
        }

        // 3. Scanner (Barcode Input)
        // Agregamos listener 'input' para que también filtre mientras se escribe/escanea
        barcodeInput.addEventListener('input', function(e) {
            filterProducts();
        });

        barcodeInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const code = this.value.toLowerCase().trim();
                if (!code) return;

                // Buscar producto por código EXACTO
                let foundId = null;
                const products = document.querySelectorAll('.product-item-wrapper');
                
                for (let el of products) {
                    // Usamos includes para búsqueda parcial si es manual, pero para add to cart preferimos exacto?
                    // Para add to cart via scanner (Enter) debe ser exacto o muy aproximado.
                    // Aquí mantenemos exacto para evitar agregar productos equivocados.
                    if (el.dataset.code === code) {
                        foundId = el.dataset.id;
                        break;
                    }
                }


                if (foundId) {
                    addToCart(parseInt(foundId));
                    this.value = ''; // Limpiar input
                    filterProducts(); // Restaurar grid (mostrar todo lo q coincida con el texto)
                    
                    // Feedback visual
                    const card = document.querySelector(`.product-item-wrapper[data-id="${foundId}"] .product-card`);
                    if(card) {
                        const originalBorder = card.style.borderColor;
                        card.style.borderColor = '#28a745';
                        card.style.boxShadow = '0 0 15px rgba(40, 167, 69, 0.5)';
                        setTimeout(() => {
                            card.style.borderColor = originalBorder;
                            card.style.boxShadow = '';
                        }, 500);
                    }
                } else {
                    showPosAlert('error', 'No encontrado', 'Producto no encontrado con el código: ' + code);
                    this.select();
                }
            }
        });

        // 4. Lógica de Filtrado Centralizada
        function filterProducts() {
            const textTerm = searchInput.value.toLowerCase().trim();
            const barcodeTerm = barcodeInput.value.toLowerCase().trim();
            
            const activeCategoryPill = document.querySelector('.category-pill.active');
            const categoryId = activeCategoryPill ? activeCategoryPill.dataset.category : '';

            document.querySelectorAll('.product-item-wrapper').forEach(el => {
                const name = el.dataset.name;
                const code = el.dataset.code;
                const cat = el.dataset.category;

                // Coincidencia Buscador Texto (Nombre o Código)
                const matchesText = textTerm === '' || name.includes(textTerm) || code.includes(textTerm);
                
                // Coincidencia Buscador Barcode (Solo Código)
                const matchesBarcode = barcodeTerm === '' || code.includes(barcodeTerm);

                // Coincidencia Categoría
                const matchesCat = categoryId === '' || cat === categoryId;

                // Mostrar si cumple TODAS las condiciones activas
                el.style.display = (matchesText && matchesBarcode && matchesCat) ? '' : 'none';
            });
        }

        // 5. Categorías (Carrusel y Selección)
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                // UI Toggle
                document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                
                // Actualizar iconos (visual)
                this.querySelector('i').classList.remove('text-info', 'bg-light'); 
                this.querySelector('i').classList.add('bg-primary', 'text-white');
                
                // Restaurar otros
                document.querySelectorAll('.category-pill:not(.active) i').forEach(icon => {
                    if(icon.classList.contains('fa-th-large')) return; // Excepto TODO
                    icon.classList.remove('bg-primary', 'text-white');
                    icon.classList.add('text-info');
                });                

                filterProducts();
            });
        });

        // Controles de Scroll Carrusel
        document.getElementById('scrollLeft').addEventListener('click', () => {
            categoryContainer.scrollBy({ left: -200, behavior: 'smooth' });
        });
        document.getElementById('scrollRight').addEventListener('click', () => {
            categoryContainer.scrollBy({ left: 200, behavior: 'smooth' });
        });
        
        // Auto-Scroll con mouse (Drag)
        let isDown = false;
        let startX;
        let scrollLeft;
        
        categoryContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            categoryContainer.classList.add('active');
            startX = e.pageX - categoryContainer.offsetLeft;
            scrollLeft = categoryContainer.scrollLeft;
        });
        categoryContainer.addEventListener('mouseleave', () => { isDown = false; categoryContainer.classList.remove('active'); });
        categoryContainer.addEventListener('mouseup', () => { isDown = false; categoryContainer.classList.remove('active'); });
        categoryContainer.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - categoryContainer.offsetLeft;
            const walk = (x - startX) * 2; // Velocidad del scroll
            categoryContainer.scrollLeft = scrollLeft - walk;
        });


        // 6. Reset Total
        function resetAllFilters() {
            // Reset Texto
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            
            // Reset Scanner Toggle
            scannerMode.checked = false;
            scannerMode.dispatchEvent(new Event('change'));
            
            // Reset Categoria
            const allCatBtn = document.querySelector('.category-pill[data-category=""]');
            if(allCatBtn) allCatBtn.click();
            
            searchInput.focus();
        }

        // Modales y Pagos
        function openPaymentModal() {
            new bootstrap.Modal(document.getElementById('paymentModal')).show();
        }
        
        function selectPaymentMethod(btn) {
             document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
             btn.classList.add('active');
             selectedPaymentMethodId = btn.dataset.id;
        }

        function processSale() {
            const btn = document.getElementById('btnConfirmPay');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> PROCESANDO...';

            // Simulación o llamada real (mantener endpoints originales)
            fetch('/pos/sale', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    customer_id: selectedCustomerId,
                    series_id: document.getElementById('documentType').value,
                    payment_method_id: selectedPaymentMethodId,
                    items: cart, // Enviar estructura correcta
                    amount_received: parseFloat(document.getElementById('amountReceived').value) || 0,
                    send_to_sunat: true
                })
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Venta Exitosa!',
                            text: 'Comprobante: ' + res.document.full_number,
                            confirmButtonText: 'Aceptar'
                        }).then(() => { window.location.reload(); });
                    } else {
                        alert('VENTA EXITOSA: ' + res.document.full_number);
                        window.location.reload();
                    }
                } else {
                   showPosAlert('error', 'Error en Venta', res.message);
                   btn.disabled = false;
                   btn.innerText = 'CONFIRMAR COMPRA';
                }
            }).catch(e => {
                showPosAlert('error', 'Error de Conexión', 'No se pudo procesar la venta. Intente nuevamente.');
                btn.disabled = false;
                btn.innerText = 'CONFIRMAR COMPRA';
            });
        }
        
        // Cliente (Misma logica)
        function openCustomerModal() { new bootstrap.Modal(document.getElementById('customerModal')).show(); }

        document.getElementById('customerSearch').addEventListener('input', function() {
            const q = this.value;
            const resultsContainer = document.getElementById('customerResults');
            
            if(q.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }

            // 1. Busqueda Local
            fetch('/pos/customers?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if (!Array.isArray(data)) return;
                    
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(c => {
                            const safeName = c.name.replace(/'/g, "\\'");
                            html += `
                            <a href="#" class="list-group-item list-group-item-action" onclick="setCustomer('${c.id}', '${safeName}', '${c.tax_id}')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark">${c.name}</div>
                                        <small class="text-muted"><i class="fa fa-id-card me-1"></i>${c.tax_id}</small>
                                    </div>
                                    <i class="fa fa-chevron-right text-muted small"></i>
                                </div>
                            </a>`;
                        });
                        resultsContainer.innerHTML = html;
                    } else {
                        // 2. Si no hay locales y es DNI(8) o RUC(11), buscar en API
                        if (q.length === 8 || q.length === 11) {
                            resultsContainer.innerHTML = '<div class="text-center p-3"><i class="fa fa-spinner fa-spin"></i> Buscando en RENIEC/SUNAT...</div>';
                            searchExternalCustomer(q);
                        } else {
                            resultsContainer.innerHTML = '<div class="text-center p-3 text-muted">No se encontraron clientes</div>';
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    resultsContainer.innerHTML = '<div class="text-center p-3 text-danger">Error al buscar</div>';
                });
        });

        function searchExternalCustomer(docNumber) {
            const type = docNumber.length === 8 ? 'dni' : 'ruc';
            const route = type === 'dni' ? '/api/documents/query-dni?dni=' : '/api/documents/query-ruc?ruc=';
            
            fetch(route + docNumber)
                .then(r => r.json())
                .then(res => {
                    const resultsContainer = document.getElementById('customerResults');
                    if (res.success) {
                        const data = res.data;
                        const name = data.name || data.business_name;
                        const address = data.address || '';
                        const safeName = name.replace(/'/g, "\\'");
                        const safeAddress = address.replace(/'/g, "\\'");
                        
                        // Botón para crear al vuelo
                        resultsContainer.innerHTML = `
                            <div class="alert alert-success m-2">
                                <i class="fa fa-check-circle me-1"></i> Encontrado en ${type.toUpperCase()}
                            </div>
                            <a href="#" class="list-group-item list-group-item-action bg-light border-primary" 
                               onclick="createAndSetCustomer('${docNumber}', '${safeName}', '${safeAddress}', '${type}')">
                                <div class="fw-bold text-primary">NUEVO: ${name}</div>
                                <div class="small text-muted">${address}</div>
                                <div class="mt-1 small text-success fw-bold">Click para agregar</div>
                            </a>`;
                    } else {
                        resultsContainer.innerHTML = '<div class="text-center p-3 text-muted">No encontrado en SUNAT/RENIEC</div>';
                    }
                })
                .catch(e => {
                     document.getElementById('customerResults').innerHTML = '<div class="text-center p-3 text-muted">No encontrado (Error API)</div>';
                });
        }

        function createAndSetCustomer(taxId, name, address, type) {
            // Identidad: 1=DNI, 6=RUC
            const identityType = type === 'dni' ? '1' : '6';
            
            fetch('/pos/quick-customer', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify({
                    tax_id: taxId,
                    name: name,
                    address: address,
                    identity_type: identityType
                })
            })
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    setCustomer(res.customer.id, res.customer.name, res.customer.tax_id);
                } else {
                    showPosAlert('error', 'Error', 'Error al crear cliente: ' + res.message);
                }
            });
        }

        function setCustomer(id, name, tax) {
            selectedCustomerId = id;
            document.getElementById('customerName').innerText = name;
            document.getElementById('customerTaxId').innerText = tax;
            document.getElementById('selectedCustomerId').value = id;
            bootstrap.Modal.getInstance(document.getElementById('customerModal')).hide();
        }

         // Calculo de vuelto
         document.getElementById('amountReceived').addEventListener('input', function() {
            const received = parseFloat(this.value) || 0;
            const totalStr = document.getElementById('modalTotal').innerText.replace('S/ ', '');
            const total = parseFloat(totalStr) || 0;
            const change = received - total;
            const alert = document.getElementById('changeDisplay');
            if (change >= 0 && received > 0) {
                alert.style.display = 'block';
                document.getElementById('changeAmount').innerText = 'S/ ' + change.toFixed(2);
            } else {
                alert.style.display = 'none';
            }
         });

    </script>
</body>
</html>
