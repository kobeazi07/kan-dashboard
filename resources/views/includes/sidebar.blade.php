 <div class="sidebar-wrapper" id="sidebar">
     <!-- Brand Logo / Identity -->
     <a href="index.html" class="sidebar-brand">

         <span>Dashboard SLF</span>
     </a>

     <!-- Navigation Menu -->
     <div class="flex-grow-1 overflow-y-auto">
         <!-- Group: Menu -->
         <div class="sidebar-menu-section">
             <div class="sidebar-menu-title">Menu</div>

             <ul class="sidebar-menu-list">
                 <li class="sidebar-menu-item">
                     <a href="{{ route('HalamanDashboard') }}"
                         class="sidebar-menu-link {{ request()->routeIs('HalamanDashboard') ? 'active' : '' }}"
                         id="menu-dashboard" title="Dashboard">
                         <i class="bi bi-grid-fill"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>


                 <li class="sidebar-menu-item">
                     <a href="#" class="sidebar-menu-link" data-bs-toggle="collapse" data-bs-target="#menu-file"
                         aria-expanded="false" aria-controls="menu-file" id="menu-file-toggle" title="File">
                         <i class="bi bi-folder-fill"></i>
                         <span>Data PBG</span>
                         <i class="bi bi-chevron-down ms-auto"></i>
                     </a>

                     <ul class="sidebar-menu-list collapse" id="menu-file">

                         <li class="sidebar-menu-item">
                             <a href="{{ $setting->link_jetty ?? '#' }}" class="sidebar-menu-link" id="menu-jetty"
                                 title="Jetty">
                                 <i class="bi bi-water"></i>
                                 <span>Jetty</span>
                             </a>
                         </li>

                         <li class="sidebar-menu-item">
                             <a href="{{ $setting->link_refinery ?? '#' }}" class="sidebar-menu-link" id="menu-refinery"
                                 title="Refinery">
                                 <i class="bi bi-building"></i>
                                 <span>Refinery</span>
                             </a>
                         </li>

                         <li class="sidebar-menu-item">
                             <a href="{{ $setting->link_power_plant ?? '#' }}" class="sidebar-menu-link"
                                 id="menu-power-plant" title="Power Plant">
                                 <i class="bi bi-lightning-charge-fill"></i>
                                 <span>Power Plant</span>
                             </a>
                         </li>

                         <li class="sidebar-menu-item">
                             <a href="{{ $setting->link_redmud ?? '#' }}" class="sidebar-menu-link" id="menu-redmud"
                                 title="Redmud">
                                 <i class="bi bi-recycle"></i>
                                 <span>Redmud</span>
                             </a>
                         </li>

                         <li class="sidebar-menu-item">
                             <a href="{{ $setting->link_jembatan ?? '#' }}" class="sidebar-menu-link" id="menu-jembatan"
                                 title="Jembatan">
                                 <i class="bi bi-cone-striped"></i>
                                 <span>Jembatan</span>
                             </a>
                         </li>

                     </ul>
                 </li>


                 @if (auth()->check() && auth()->user()->role === 'Admin')
                     <li class="sidebar-menu-item">
                         <a href="{{ route('HalamanAdminmaps') }}"
                             class="sidebar-menu-link {{ request()->routeIs('HalamanAdminmaps') ? 'active' : '' }}"
                             id="menu-maps" title="Maps">
                             <i class="bi bi-map-fill"></i>
                             <span>Maps</span>
                         </a>
                     </li>
                     <li class="sidebar-menu-item">
                         <a href="{{ route('HalamanAdminsettings') }}"
                             class="sidebar-menu-link {{ request()->routeIs('HalamanAdminsettings') ? 'active' : '' }}"
                             id="menu-maps" title="Maps">
                             <i class="bi bi-gear-fill"></i>
                             <span>Setting</span>
                         </a>
                     </li>
                 @endif

             </ul>

         </div>

     </div>

     <!-- Sidebar Profile Card (Dynamic Footer) -->
     <div class="sidebar-profile">
         {{-- <img src="{{ asset('assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img"
             onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'"> --}}
         <div class="sidebar-profile-info">
             <div class="sidebar-profile-name"> {{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
             {{-- <div class="sidebar-profile-email">admin@email.com</div> --}}
         </div>
     </div>
 </div>
