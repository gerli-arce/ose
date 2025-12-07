<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('index');

// Authentication Routes
// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// Forgot Password Routes
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');

// Routes needing Authentication
Route::middleware(['auth'])->group(function () {
    
    // Selection Routes
    Route::get('select-company', [App\Http\Controllers\CompanySelectionController::class, 'show'])->name('select.company');
    Route::post('select-company', [App\Http\Controllers\CompanySelectionController::class, 'select'])->name('select.company.post');
    Route::get('select-branch', [App\Http\Controllers\BranchSelectionController::class, 'show'])->name('select.branch');
    Route::post('select-branch', [App\Http\Controllers\BranchSelectionController::class, 'select'])->name('select.branch.post');

    // Routes needing Context (Company + Branch)
    Route::middleware([\App\Http\Middleware\EnsureCompanySelected::class, \App\Http\Middleware\EnsureBranchSelected::class])->group(function () {
        
        Route::get('/', function () {
            return redirect()->route('dashboard');
        });
        
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::view('ecommerce', 'dashboards.ecommerce_dashboard')->name('ecommerce_dashboard');

        // Roles y Permisos
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::get('roles/{role}/permissions', [App\Http\Controllers\RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
        Route::put('roles/{role}/permissions', [App\Http\Controllers\RolePermissionController::class, 'update'])->name('roles.permissions.update');
    
        // Usuarios
        Route::resource('users', \App\Http\Controllers\UserController::class);
    
        // Configuración Empresa
        // Configuración Empresa (Enhanced)
        Route::get('/settings/company', [\App\Http\Controllers\CompanySettingsController::class, 'index'])->name('settings.company.index');
        Route::post('/settings/company/general', [\App\Http\Controllers\CompanySettingsController::class, 'updateGeneral'])->name('settings.company.general');
        Route::post('/settings/company/electronic', [\App\Http\Controllers\CompanySettingsController::class, 'updateElectronic'])->name('settings.company.electronic');
        Route::post('/settings/company/billing', [\App\Http\Controllers\CompanySettingsController::class, 'updateBilling'])->name('settings.company.billing');
    
        // Sucursales
        Route::resource('branches', App\Http\Controllers\BranchController::class);
    
        // Inventario
        Route::resource('inventory/categories', ProductCategoryController::class)->names('categories');
        Route::resource('inventory/warehouses', WarehouseController::class)->names('warehouses');
        Route::resource('inventory/products', ProductController::class)->names('products');
        Route::resource('inventory/movements', StockMovementController::class)->only(['index', 'create', 'store'])->names('movements');
    
        // Sales Routes
        Route::get('sales/invoices/search-products', [InvoiceController::class, 'searchProducts'])->name('invoices.search.products');
        Route::resource('sales/series', SeriesController::class)->names('series');
        Route::resource('sales/invoices', InvoiceController::class)->names('invoices');
        
        // Moved to Compras section below to avoid route conflict

    // Cash Routes
    Route::get('cash', [App\Http\Controllers\CashController::class, 'index'])->name('cash.index');
    Route::post('cash/open', [App\Http\Controllers\CashController::class, 'openSession'])->name('cash.open');
    Route::post('cash/close/{id}', [App\Http\Controllers\CashController::class, 'closeSession'])->name('cash.close');
    Route::get('cash/{id}', [App\Http\Controllers\CashController::class, 'show'])->name('cash.show');
    Route::post('cash/movement', [App\Http\Controllers\CashController::class, 'storeMovement'])->name('cash.movement');

    // Bank Routes
    Route::get('banks', [App\Http\Controllers\BankController::class, 'index'])->name('banks.index');
    Route::post('banks', [App\Http\Controllers\BankController::class, 'store'])->name('banks.store');
    Route::get('banks/{id}', [App\Http\Controllers\BankController::class, 'show'])->name('banks.show');
    Route::post('banks/transaction', [App\Http\Controllers\BankController::class, 'storeTransaction'])->name('banks.transaction');
    Route::post('banks/transactions/{id}/toggle', [App\Http\Controllers\BankController::class, 'toggleReconciled'])->name('banks.transactions.toggle');

    // Reports Routes
    Route::get('reports/sales', [App\Http\Controllers\ReportSalesController::class, 'index'])->name('reports.sales');
    Route::get('reports/taxes', [App\Http\Controllers\ReportTaxesController::class, 'index'])->name('reports.taxes');
    Route::get('reports/products', [App\Http\Controllers\ReportProductsController::class, 'index'])->name('reports.products');
    Route::get('reports/receivables', [App\Http\Controllers\ReportReceivablesController::class, 'index'])->name('reports.receivables');
    Route::get('reports/payables', [App\Http\Controllers\ReportPayablesController::class, 'index'])->name('reports.payables');
    Route::get('reports/customers', [App\Http\Controllers\ReportController::class, 'customers'])->name('reports.customers');

    // SaaS Admin Routes
    Route::middleware(['auth', 'is_super_admin'])->prefix('saas')->name('saas.')->group(function () {
        Route::resource('plans', App\Http\Controllers\SaasPlanController::class);
        Route::get('companies', [App\Http\Controllers\SaasCompanyController::class, 'index'])->name('companies.index');
        Route::get('companies/{id}', [App\Http\Controllers\SaasCompanyController::class, 'show'])->name('companies.show');
        Route::post('companies/{id}/status', [App\Http\Controllers\SaasCompanyController::class, 'updateStatus'])->name('companies.status');
        Route::post('companies/{id}/subscription', [App\Http\Controllers\SaasSubscriptionController::class, 'update'])->name('subscriptions.update');
    });

    // SaaS Admin Routes
    Route::group(['prefix' => 'saas'], function() {
        Route::get('tenants', [App\Http\Controllers\SaasController::class, 'tenants'])->name('saas.tenants');
        Route::post('tenants/{id}/toggle', [App\Http\Controllers\SaasController::class, 'toggleTenantStatus'])->name('saas.tenants.toggle');
        Route::get('plans', [App\Http\Controllers\SaasController::class, 'plans'])->name('saas.plans.index');
        Route::post('plans', [App\Http\Controllers\SaasController::class, 'storePlan'])->name('saas.plans.store');
        Route::post('assign-plan', [App\Http\Controllers\SaasController::class, 'assignPlan'])->name('saas.plan.assign');
    });

    // Contactos
    Route::resource('contacts', \App\Http\Controllers\ContactController::class);

    // Inventario (Enhanced)
    Route::resource('inventory/products', \App\Http\Controllers\ProductController::class)->names('products');
    Route::resource('inventory/warehouses', \App\Http\Controllers\WarehouseController::class)->names('warehouses');
    Route::get('inventory/movements', [\App\Http\Controllers\StockMovementController::class, 'index'])->name('movements.index');
    Route::get('inventory/movements/create', [\App\Http\Controllers\StockMovementController::class, 'create'])->name('movements.create');
    Route::post('inventory/movements', [\App\Http\Controllers\StockMovementController::class, 'store'])->name('movements.store');

    // Ventas
    Route::resource('sales/documents', \App\Http\Controllers\SalesDocumentController::class)->names('sales.documents')->except(['edit', 'update', 'destroy']);
    Route::get('sales/invoices/create', [\App\Http\Controllers\SalesDocumentController::class, 'create'])->name('sales.invoices.create'); // explicit alias if needed or rely on resource
    Route::post('sales/payments', [\App\Http\Controllers\SalesPaymentController::class, 'store'])->name('sales.payments.store');
    Route::get('sales/edocs/{id}/send', [\App\Http\Controllers\EDocumentController::class, 'send'])->name('edocs.send');

    // Compras
    Route::resource('purchases/documents', \App\Http\Controllers\PurchaseDocumentController::class)->names('purchases.documents')->except(['edit', 'update', 'destroy']);
    Route::post('purchases/payments', [\App\Http\Controllers\PurchasePaymentController::class, 'store'])->name('purchases.payments.store');
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);

    // Widgets
    Route::view('general-widget', 'widgets.general_widget')->name('general-widget');
    Route::view('chart-widget', 'widgets.chart_widget')->name('chart-widget');

    // Page layout
    Route::view('box-layout', 'page_layout.box_layout')->name('box-layout');
    Route::view('layout-rtl', 'page_layout.layout_rtl')->name('layout-rtl');
    Route::view('layout-dark', 'page_layout.layout_dark')->name('layout-dark');
    Route::view('hide-on-scroll', 'page_layout.hide_on_scroll')->name('hide-on-scroll');
    Route::view('footer-light', 'page_layout.footer_light')->name('footer-light');
    Route::view('footer-dark', 'page_layout.footer_dark')->name('footer-dark');
    Route::view('footer-fixed', 'page_layout.footer_fixed')->name('footer-fixed');

    // Project
    Route::view('projects', 'project.projects')->name('projects');
    Route::view('projectcreate', 'project.projectcreate')->name('projectcreate');

    // File Manager
    Route::view('file-manager', 'file_manager')->name('file_manager');

    // Kanban
    Route::view('kanban', 'kanban')->name('kanban');

    // Ecommerce
    Route::view('product', 'ecommerce.product')->name('product');
    Route::view('add_product', 'ecommerce.add_product')->name('add_product');
    Route::view('page-product', 'ecommerce.product_page')->name('page-product');
    Route::view('list-products', 'ecommerce.list_products')->name('list-products');
    Route::view('payment-details', 'ecommerce.payment_details')->name('payment-details');
    Route::view('order-history', 'ecommerce.order_history')->name('order-history');
    Route::view('invoice-template', 'ecommerce.invoice_template')->name('invoice-template');
    Route::view('cart', 'ecommerce.cart')->name('cart');
    Route::view('list-wish', 'ecommerce.list_wish')->name('list-wish');
    Route::view('checkout', 'ecommerce.checkout')->name('checkout');
    Route::view('pricing', 'ecommerce.pricing')->name('pricing');

    // Email
    Route::view('email-compose', 'email.email_compose')->name('email_compose');
    Route::view('email-inbox', 'email.email_inbox')->name('email_inbox');
    Route::view('email-read', 'email.email_read')->name('email_read');

    // Chat
    Route::view('chat', 'chat.chat')->name('chat');
    Route::view('video-chat', 'chat.chat_video')->name('video_chat');

    // Users
    Route::view('edit-profile', 'users.edit_profile')->name('edit-profile');
    Route::view('user-cards', 'users.user_cards')->name('user-cards');
    Route::view('user-profile', 'users.user_profile')->name('user-profile');

    // Bookmark
    Route::view('bookmark', 'bookmark')->name('bookmark');

    // Contacts (Template route - disabled, using resource route instead)
    // Route::view('contacts', 'contacts')->name('contacts');

    // Task
    Route::view('tasks', 'tasks')->name('task');

    // Calendar
    Route::view('calendar_basic', 'calendar_basic')->name('calendar_basic');

    // Social APP
    Route::view('social_app', 'social_app')->name('social_app');

    // To-do
    Route::view('to_do', 'to_do')->name('to_do');

    // Search
    Route::view('search', 'search')->name('search');

    // Forms
    // Forms -> Form Controls
    Route::view('form-validation', 'forms.form_contols.form_validation')->name('form-validation');
    Route::view('base-input', 'forms.form_contols.base_input')->name('base-input');
    Route::view('radio-checkbox-control', 'forms.form_contols.radio_checkbox_control')->name('radio-checkbox-control');
    Route::view('input-group', 'forms.form_contols.input_group')->name('input-group');
    Route::view('megaoptions', 'forms.form_contols.megaoptions')->name('megaoptions');

    // Forms -> Form Widgets
    Route::view('datepicker', 'forms.form_widgets.datepicker')->name('datepicker');
    Route::view('time-picker', 'forms.form_widgets.time_picker')->name('time-picker');
    Route::view('datetimepicker', 'forms.form_widgets.datetimepicker')->name('datetimepicker');
    Route::view('daterangepicker', 'forms.form_widgets.daterangepicker')->name('daterangepicker');
    Route::view('touchspin', 'forms.form_widgets.touchspin')->name('touchspin');
    Route::view('select2', 'forms.form_widgets.select2')->name('select2');
    Route::view('switch', 'forms.form_widgets.switch')->name('switch');
    Route::view('typeahead', 'forms.form_widgets.typeahead')->name('typeahead');
    Route::view('clipboard', 'forms.form_widgets.clipboard')->name('clipboard');

    // Forms -> Form Layout
    Route::view('default-form', 'forms.form_layout.default_form')->name('default-form');
    Route::view('form-wizard', 'forms.form_layout.form_wizard')->name('form-wizard');
    Route::view('form-wizard-two', 'forms.form_layout.form_wizard_two')->name('form-wizard-two');
    Route::view('form-wizard-three', 'forms.form_layout.form_wizard_three')->name('form-wizard-three');

    // Tables
    Route::view('bootstrap-basic-table', 'tables.bootstrap_tables.bootstrap_basic_table')->name('bootstrap-basic-table');
    Route::view('bootstrap-sizing-table', 'tables.bootstrap_sizing_table')->name('bootstrap-sizing-table');
    Route::view('bootstrap-border-table', 'tables.bootstrap_border_table')->name('bootstrap-border-table');
    Route::view('bootstrap-styling-table', 'tables.bootstrap_styling_table')->name('bootstrap-styling-table');
    Route::view('table-components', 'tables.bootstrap_tables.table_components')->name('table-components');

    // Data Tables
    Route::view('datatable-basic-init', 'tables.data_tables.datatable_basic_init')->name('datatable-basic-init');
    Route::view('datatable-advance-init', 'tables.datatable_advance_init')->name('datatable-advance-init');
    Route::view('datatable-styling', 'tables.datatable_styling')->name('datatable-styling');
    Route::view('datatable-ajax', 'tables.datatable_ajax')->name('datatable-ajax');
    Route::view('datatable-server-side', 'tables.datatable_server_side')->name('datatable-server-side');
    Route::view('datatable-plugin', 'tables.datatable_plugin')->name('datatable-plugin');
    Route::view('datatable-api', 'tables.data_tables.datatable_api')->name('datatable-api');
    Route::view('datatable-data-source', 'tables.data_tables.datatable_data_source')->name('datatable-data-source');

    // Extension Data Tables
    Route::view('datatable-ext-autofill', 'tables.ex_data_tables.datatable_ext_autofill')->name('datatable-ext-autofill');
    
    // JS Grid_Table
    Route::view('js-grid-table', 'tables.js_grid_table')->name('js_grid_table');

    // Ui Kits
    Route::view('state-color', 'ui_kits.state_color')->name('state-color');
    Route::view('typography', 'ui_kits.typography')->name('typography');
    Route::view('avatars', 'ui_kits.avatars')->name('avatars');
    Route::view('helper-classes', 'ui_kits.helper_classes')->name('helper-classes');
    Route::view('grid', 'ui_kits.grid')->name('grid');
    Route::view('tag-pills', 'ui_kits.tag_pills')->name('tag-pills');
    Route::view('progress-bar', 'ui_kits.progress_bar')->name('progress-bar');
    Route::view('modal', 'ui_kits.modal')->name('modal');
    Route::view('alert', 'ui_kits.alert')->name('alert');
    Route::view('popover', 'ui_kits.popover')->name('popover');
    Route::view('tooltip', 'ui_kits.tooltip')->name('tooltip');
    Route::view('loader', 'ui_kits.loader')->name('loader');
    Route::view('dropdown', 'ui_kits.dropdown')->name('dropdown');
    Route::view('according', 'ui_kits.according')->name('according');
    Route::view('tab-bootstrap', 'ui_kits.tabs.tab_bootstrap')->name('tab_bootstrap');
    Route::view('tab-line', 'ui_kits.tabs.tab_line')->name('tab_line');
    Route::view('box-shadow', 'ui_kits.box_shadow')->name('box-shadow');
    Route::view('list', 'ui_kits.list')->name('list');

    // Bonus Ui
    Route::view('scrollable', 'bonus_ui.scrollable')->name('scrollable');
    Route::view('tree', 'bonus_ui.tree')->name('tree');
    Route::view('bootstrap-notify', 'bonus_ui.bootstrap-notify')->name('bootstrap-notify');
    Route::view('rating', 'bonus_ui.rating')->name('rating');
    Route::view('dropzone', 'bonus_ui.dropzone')->name('dropzone');
    Route::view('tour', 'bonus_ui.tour')->name('tour');
    Route::view('sweet-alert2', 'bonus_ui.sweet-alert2')->name('sweet-alert2');
    Route::view('modal-animated', 'bonus_ui.modal-animated')->name('modal-animated');
    Route::view('owl-carousel', 'bonus_ui.owl-carousel')->name('owl-carousel');
    Route::view('ribbons', 'bonus_ui.ribbons')->name('ribbons');
    Route::view('pagination', 'bonus_ui.pagination')->name('pagination');
    Route::view('breadcrumb', 'bonus_ui.breadcrumb')->name('breadcrumb');
    Route::view('range-slider', 'bonus_ui.range-slider')->name('range-slider');
    Route::view('image-cropper', 'bonus_ui.image-cropper')->name('image-cropper');
    Route::view('sticky', 'bonus_ui.sticky')->name('sticky');
    Route::view('basic-card', 'bonus_ui.basic_card')->name('basic-card');
    Route::view('creative-card', 'bonus_ui.creative_card')->name('creative-card');
    Route::view('tabbed-card', 'bonus_ui.tabbed-card')->name('tabbed-card');
    Route::view('dragable-card', 'bonus_ui.dragable-card')->name('dragable-card');
    Route::view('timeline_v_1', 'bonus_ui.timeline_v_1')->name('timeline_v_1');
    Route::view('timeline_v_2', 'bonus_ui.timeline_v_2')->name('timeline_v_2');
    Route::view('photoswipe', 'bonus_ui.photoswipe')->name('photoswipe');

    // Builders
    Route::view('form-builder-1', 'builders.form_builder_1')->name('form-builder-1');
    Route::view('form-builder-2', 'builders.form_builder_2')->name('form-builder-2');
    Route::view('pagebuild', 'builders.page_build')->name('pagebuild');
    Route::view('button-builder', 'builders.button_builder')->name('button-builder');

    // Animation
    Route::view('animate', 'animation.animate')->name('animate');
    Route::view('scroll-reval', 'animation.scroll_reval')->name('scroll-reval');
    Route::view('aos', 'animation.aos')->name('aos');
    Route::view('tilt', 'animation.tilt')->name('tilt');
    Route::view('wow', 'animation.wow')->name('wow');

    // Icons
    Route::view('flag-icon', 'icons.flag_icon')->name('flag-icon');
    Route::view('font-awesome', 'icons.font_awesome')->name('font-awesome');
    Route::view('ico-icon', 'icons.ico_icon')->name('ico-icon');
    Route::view('themify-icon', 'icons.themify_icon')->name('themify-icon');
    Route::view('feather-icon', 'icons.feather_icon')->name('feather-icon');
    Route::view('whether-icon', 'icons.whether_icon')->name('whether-icon');

    // Buttons
    Route::view('buttons', 'buttons.buttons')->name('buttons');
    Route::view('buttons-flat', 'buttons.buttons_flat')->name('buttons-flat');
    Route::view('buttons-edge', 'buttons.buttons_edge')->name('buttons-edge');
    Route::view('raised-button', 'buttons.raised_button')->name('raised-button');
    Route::view('button-group', 'buttons.button_group')->name('button-group');

    // Charts
    Route::view('chart-apex', 'charts.chart_apex')->name('chart-apex');
    Route::view('chart-google', 'charts.chart_google')->name('chart-google');
    Route::view('chart-sparkline', 'charts.chart_sparkline')->name('chart-sparkline');
    Route::view('chart-flot', 'charts.chart_flot')->name('chart-flot');
    Route::view('chart-knob', 'charts.chart_knob')->name('chart-knob');
    Route::view('chart-morris', 'charts.chart_morris')->name('chart-morris');
    Route::view('chartjs', 'charts.chartjs')->name('chartjs');
    Route::view('chartist', 'charts.chartist')->name('chartist');
    Route::view('chart-peity', 'charts.chart_peity')->name('chart-peity');

    // Pages
    Route::view('sample_page', 'pages.sample_page')->name('sample_page');
    Route::view('internationalization', 'pages.internationalization')->name('internationalization');

    // Others
    Route::view('error-400', 'others.error_400')->name('error-400');
    Route::view('error-401', 'others.error_401')->name('error-401');
    Route::view('error-403', 'others.error_403')->name('error-403');
    Route::view('error-404', 'others.error_404')->name('error-404');
    Route::view('error-500', 'others.error_500')->name('error-500');
    Route::view('error-503', 'others.error_503')->name('error-503');
    
    // Error Pages (Templates)
    Route::view('error-page1', 'others.error_page.error_page1')->name('error-page1');
    Route::view('error-page2', 'others.error_page.error_page2')->name('error-page2');
    Route::view('error-page3', 'others.error_page.error_page3')->name('error-page3');
    Route::view('error-page4', 'others.error_page.error_page4')->name('error-page4');
    Route::view('error-page5', 'others.error_page.error_page5')->name('error-page5');

    // Email Templates
    Route::view('basic-template', 'others.email_templates.basic-template')->name('basic-template');
    Route::view('email-header', 'others.email_templates.email-header')->name('email-header');
    Route::view('template-email', 'others.email_templates.template-email')->name('template-email');
    Route::view('template-email-2', 'others.email_templates.template-email-2')->name('template-email-2');
    Route::view('ecommerce-templates', 'others.email_templates.ecommerce-templates')->name('ecommerce-templates');
    Route::view('email-order-success', 'others.email_templates.email-order-success')->name('email-order-success');

    Route::view('maintenance', 'others.authentication.maintenance')->name('maintenance');
    Route::view('login-one', 'others.authentication.login_one')->name('login_one');
    Route::view('login-two', 'others.authentication.login_two')->name('login_two');
    Route::view('login-bs-validation', 'others.authentication.login_bs_validation')->name('login-bs-validation');
    Route::view('login-bs-tt-validation', 'others.authentication.login_bs_tt_validation')->name('login-bs-tt-validation');
    Route::view('login-sa-validation', 'others.authentication.login_sa_validation')->name('login-sa-validation');
    Route::view('sign-up', 'others.authentication.sign_up')->name('sign-up');
    Route::view('sign-up-one', 'others.authentication.sign_up_one')->name('sign-up-one');
    Route::view('sign-up-two', 'others.authentication.sign_up_two')->name('sign-up-two');
    Route::view('sign-up-wizard', 'others.authentication.sign_up_wizard')->name('sign-up-wizard');
    Route::view('unlock', 'others.authentication.unlock')->name('unlock');
    Route::view('forget-password', 'others.authentication.forget_password')->name('forget-password');
    Route::view('reset-password', 'others.authentication.reset_password')->name('reset-password');
    Route::view('comingsoon', 'others.coming_soon.comingsoon')->name('comingsoon');
    Route::view('comingsoon-bg-video', 'others.coming_soon.comingsoon_bg_video')->name('comingsoon-bg-video');
    Route::view('comingsoon-bg-img', 'others.coming_soon.comingsoon_bg_img')->name('comingsoon-bg-img');

    // Gallery
    Route::view('gallery', 'gallery.gallery')->name('gallery');
    Route::view('gallery-with-description', 'gallery.gallery_with_description')->name('gallery-with-description');
    Route::view('masonry-gallery', 'gallery.gallery_masonry')->name('masonry-gallery');
    Route::view('masonry-gallery-with-disc', 'gallery.gallery_masonry_with_description')->name('masonry-gallery-with-disc');
    Route::view('gallery-hover', 'gallery.gallery_hover')->name('gallery-hover');

    // Blog
    Route::view('blog', 'blog.blog')->name('blog');
    Route::view('single-blog', 'blog.blog_single')->name('single-blog');
    Route::view('add-post', 'blog.add_post')->name('add-post');

    // FAQ
    Route::view('faq-page', 'faq_page')->name('faq_page');

    // Job Search
    Route::view('job-cards-view', 'job_search.job_cards_view')->name('job-cards-view');
    Route::view('job-list-view', 'job_search.job_list_view')->name('job-list-view');
    Route::view('job-details', 'job_search.job_details')->name('job-details');
    Route::view('job-apply', 'job_search.job_apply')->name('job-apply');

    // Learning
    Route::view('learning-list-view', 'learning.learning_list_view')->name('learning-list-view');
    Route::view('learning-detailed', 'learning.learning_detailed')->name('learning-detailed');

    // Maps
    Route::view('map_js', 'maps.map_js')->name('map_js');
    Route::view('vector-map', 'maps.vector_map')->name('vector-map');

    // Editors
    Route::view('summernote', 'editors.summernote')->name('summernote');
    Route::view('ckeditor', 'editors.ckeditor')->name('ckeditor');
    Route::view('simple-mde', 'editors.simple_mde')->name('simple-mde');
    Route::view('ace-code-editor', 'editors.ace_code_editor')->name('ace_code_editor');

    // Knowledgebase
    Route::view('knowledgebase', 'knowledgebase.knowledgebase')->name('knowledgebase');
    Route::view('knowledge-category', 'knowledgebase.knowledge_category')->name('knowledge-category');
    Route::view('knowledge-detail', 'knowledgebase.knowledge_detail')->name('knowledge-detail');

    // Support Ticket
    Route::view('support-ticket', 'support-ticket')->name('support-ticket');
    });
});
