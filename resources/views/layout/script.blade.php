   <!-- latest jquery-->
   <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
   <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
   <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
   <!-- feather icon js-->
   <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
   <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
   <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
   <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
   <!-- Sidebar jquery-->
   <script src="{{ asset('assets/js/config.js') }}"></script>
   <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
   <script>
      // Quita el estado preload cuando todo está listo para evitar FOUC
      window.addEventListener('load', function() {
         document.body.classList.remove('preload');
      });
   </script>
   @yield('scripts')
   <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
   <!-- Theme js-->
   <script src="{{ asset('assets/js/script.js') }}"></script>
   <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
