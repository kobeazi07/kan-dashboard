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
                     <a href="index.html" class="sidebar-menu-link active" id="menu-overview" title="Overview">
                         <i class="bi bi-grid-fill"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>
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
