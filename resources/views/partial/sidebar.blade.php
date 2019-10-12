@section('sidebar')
    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class=" nav-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : ''}}"><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i><span class="menu-title" data-i18n="nav.dash.main">Dashboard</span></a>                
            </li>
             <li class=" nav-item {{ Route::currentRouteName() == 'member-request' ? 'active' : ''}}"><a href="{{ route('member-request') }}"><i class="fa fa-users"></i><span class="menu-title" data-i18n="nav.dash.main">Member Request</span></a>                
            </li>
            <li class=" nav-item {{ Route::currentRouteName() == 'user-management' ? 'active' : '' || Route::currentRouteName() == 'user-qr-code-view' ? 'active' : '' || Route::currentRouteName() == 'user-add' ? 'active' : ''}}"><a href="{{ route('user-management') }}"><i class="fa fa-user"></i><span class="menu-title" data-i18n=""> Users</span></a>
            	<ul class="menu-content">
            			<li class=" menu-item {{ Route::currentRouteName() == 'user-management' ? 'active' : '' || Route::currentRouteName() == 'user-add' ? 'active' : '' || Route::currentRouteName() == 'user-qr-code-view' ? 'active' : '' || Route::currentRouteName() == 'user-edit' ? 'active' : ''}}"><a href="{{ route('user-management') }}"><i class="fa fa-user"></i><span class="menu-title" data-i18n=""> Users</span></a> 
            			</li>
                        <li class=" menu-item {{ Route::currentRouteName() == 'pastor-management' ? 'active' : '' || Route::currentRouteName() == 'pastor-add' ? 'active' : '' || Route::currentRouteName() == 'pastor-edit' ? 'active' : ''}}"><a href="{{ route('pastor-management') }}"><i class="fa fa-user"></i><span class="menu-title" data-i18n=""> Pastor</span></a>
                        </li>
	            		<li class=" menu-item {{ Route::currentRouteName() == 'user-setting-management' ? 'active' : '' || Route::currentRouteName() == 'user-setting-edit' ? 'active' : ''}}"><a href="{{ route('user-setting-management') }}"><i class="fa fa-cog"></i><span class="menu-title" data-i18n=""> Settings</span></a>
	            		</li>
                        <li class=" menu-item {{ Route::currentRouteName() == 'user-card-management' ? 'active' : ''}}"><a href="{{ route('user-card-management') }}"><i class="fa fa-id-card"></i><span class="menu-title" data-i18n=""> Cards</span></a>
            			</li>

            			<li class=" nav-item {{ Route::currentRouteName() == 'user-role-management' ? 'active' : '' || Route::currentRouteName() == 'user-role-add' ||  Route::currentRouteName() == 'user-role-edit' ? 'active' : ''}}"><a href="{{ route('user-role-management') }}"><i class="fa fa-link"></i><span class="menu-title" data-i18n=""> Roles</span></a>
            			</li>
                    </ul>

            </li>
            <li class=" nav-item {{ Route::currentRouteName() == 'project-management' ? 'active' : '' || Route::currentRouteName() == 'user-add' ? 'active' : '' || Route::currentRouteName() == 'church-add' ? 'active' : '' || Route::currentRouteName() == 'church-edit' ? 'active' : '' || Route::currentRouteName() == 'project-add' ? 'active' : '' || Route::currentRouteName() == 'project-slab-add' ? 'active' : '' || Route::currentRouteName() == 'project-slab-edit' ? 'active' : '' || Route::currentRouteName() == 'qr-code-view' ? 'active' : '' || Route::currentRouteName() == 'fundname-add' ? 'active' : '' || Route::currentRouteName() == 'fundname-edit' ? 'active' : '' || Route::currentRouteName() == 'scripture-edit' ? 'active' : '' || Route::currentRouteName() == 'event-add' ? 'active' : '' || Route::currentRouteName() == 'event-edit' ? 'active' : '' || Route::currentRouteName() == 'task-add' || Route::currentRouteName() == 'church-qr-code-view' ? 'active' : '' || Route::currentRouteName() == 'task-edit' ? 'active' : '' || Route::currentRouteName() == 'task-group-management' ? 'active' : '' || Route::currentRouteName() == 'task-group-add' ? 'active' : '' || Route::currentRouteName() == 'edit-group-task' ? 'active' : '' }}"><a href="{{ route('project-management') }}"><i class="fa fa-church"></i><span class="menu-title" data-i18n=""> Churches</span></a>
            		<ul class="menu-content">	
						<li class=" menu-item {{ Route::currentRouteName() == 'church-management' ? 'active' : '' || Route::currentRouteName() == 'church-add' ? 'active' : '' || Route::currentRouteName() == 'church-edit' || Route::currentRouteName() == 'church-qr-code-view' ? 'active' : ''}}"><a href="{{ route('church-management') }}"><i class="fa fa-user"></i><span class="menu-title" data-i18n=""> Church</span></a>
            			</li>
                       <li class=" menu-item {{ Route::currentRouteName() == 'project-management' ? 'active' : '' || Route::currentRouteName() == 'project-add' ? 'active' : ''}}"><a href="{{ route('project-management') }}"><i class="fa fa-list-ul"></i><span class="menu-title" data-i18n=""> Projects</span></a>
            			</li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'project-slab-management' ? 'active' : '' || Route::currentRouteName() == 'project-slab-add' ? 'active' : '' || Route::currentRouteName() == 'project-slab-edit' ? 'active' : ''}}"><a href="{{ route('project-slab-management') }}"><i class="fa fa-file-invoice"></i><span class="menu-title" data-i18n=""> Project Slabs</span></a>
            			</li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'qr-code-management' ? 'active' : '' || Route::currentRouteName() == 'qr-code-view' ? 'active' : ''}}"><a href="{{ route('qr-code-management') }}"><i class="fa fa-qrcode"></i><span class="menu-title" data-i18n=""> QR Codes</span></a>
            			</li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'fundname-management' ? 'active' : '' || Route::currentRouteName() == 'fundname-add' ? 'active' : '' || Route::currentRouteName() == 'fundname-edit' ? 'active' : '' || Route::currentRouteName() == 'fundname-add' ? 'active' : '' || Route::currentRouteName() == 'fundname-edit' ? 'active' : ''}}"><a href="{{ route('fundname-management')}}"><i class="fa fa-money-bill"></i><span class="menu-title" data-i18n=""> Fund Names</span></a>
            			</li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'scripture-management' ? 'active' : '' || Route::currentRouteName() == 'scripture-edit' ? 'active' : '' || Route::currentRouteName() == 'scripture-edit' ? 'active' : ''}}"><a href="{{ route('scripture-management') }}"><i class="fa fa-book"></i><span class="menu-title" data-i18n=""> Scriptures</span></a>
            			</li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'event-management' ? 'active' : '' || Route::currentRouteName() == 'event-add' ? 'active' : '' || Route::currentRouteName() == 'event-add' ? 'active' : '' || Route::currentRouteName() == 'event-edit' ? 'active' : ''}}"><a href="{{ route('event-management') }}"><i class="fa fa-calendar-alt"></i><span class="menu-title" data-i18n=""> Events</span></a>
            			</li>
            		<!-- 	<li class=" nav-item {{ Route::currentRouteName() == 'task-management' ? 'active' : '' || Route::currentRouteName() == 'task-add' ? 'active' : '' || Route::currentRouteName() == 'task-edit' ? 'active' : ''}}"><a href="{{ route('task-management') }}"><i class="fa fa-tasks"></i><span class="menu-title" data-i18n=""> Tasks</span></a>
            			</li>
                        <li class=" nav-item {{ Route::currentRouteName() == 'task-group-management' ? 'active' : '' || Route::currentRouteName() == 'task-group-add' ? 'active' : '' || Route::currentRouteName() == 'edit-group-task' ? 'active' : ''}}"><a href="{{ route('task-group-management') }}"><i class="fa fa-tasks"></i><span class="menu-title" data-i18n=""> Tasks Group</span></a>
                        </li> -->
                        <li class=" nav-item {{ Route::currentRouteName() == 'referrar-management' ? 'active' : ''}}"><a href="{{ route('referrar-management') }}"><i class="fa fa-user-plus"></i><span class="menu-title" data-i18n=""> Referrar</span></a>
                        </li>
                        
                    </ul>
            </li>
            
            <li class=" nav-item {{ Route::currentRouteName() == 'payment-management' ? 'active' : '' || Route::currentRouteName() == 'payment-view' ? 'active' : ''}}"><a href="{{ route('payment-management') }}"><i class="fa fa-credit-card"></i><span class="menu-title" data-i18n=""> Payment/Donation</span></a>
            </li>
           
            
            <li class=" nav-item {{ Route::currentRouteName() == 'cms-management' ? 'active' : '' || Route::currentRouteName() == 'cms-management-add' ? 'active' : '' || Route::currentRouteName() == 'cms-management-edit' ? 'active' : ''}}"><a href="{{ route('cms-management')}}"><i class="fa fa-desktop"></i><span class="menu-title" data-i18n=""> CMS Pages</span></a>
            </li>
              <!-- declaration cms -->
            <li class=" nav-item {{ Route::currentRouteName() == 'declaration-cms' ? 'active' : '' || Route::currentRouteName() == 'declaration-cms-management-add' ? 'active' : ''}}"><a href="{{ route('declaration-cms')}}"><i class="fa fa-desktop"></i><span class="menu-title" data-i18n="">Declaration CMS</span></a>
            </li>
            <!-- declaration cms -->
            <li class=" nav-item {{ Route::currentRouteName() == 'global-setting-management' ? 'active' : '' || Route::currentRouteName() == 'global-setting-add' ? 'active' : '' || Route::currentRouteName() == 'global-setting-edit' ? 'active' : '' || Route::currentRouteName() == 'email-template-add' ? 'active' : ''}}"><a href="{{ route('global-setting-management') }}"><i class="fa fa-cog"></i><span class="menu-title" data-i18n=""> Settings</span></a>
            		<ul class="menu-content">	            		
                        <li class=" menu-item {{ Route::currentRouteName() == 'global-setting-management' ? 'active' : '' || Route::currentRouteName() == 'global-setting-add' ? 'active' : '' || Route::currentRouteName() == 'global-setting-edit' ? 'active' : ''}}"><a href="{{ route('global-setting-management') }}"><i class="fa fa-info"></i><span class="menu-title" data-i18n=""> General</span></a>
                        </li>
                        <li class=" menu-item {{ Route::currentRouteName() == 'add-project-image-setting' ? 'active' : ''}}"><a href="{{ route('add-project-image-setting') }}"><i class="fa fa-info"></i><span class="menu-title" data-i18n=""> Project default images</span></a>
                        </li>
            			<li class=" nav-item {{ Route::currentRouteName() == 'email-template-management' ? 'active' : '' || Route::currentRouteName() == 'email-template-add' ? 'active' : '' || Route::currentRouteName() == 'email-template-edit' ? 'active' : ''}}"><a href="{{ route('email-template-management')}}"><i class="fa fa-envelope"></i><span class="menu-title" data-i18n="">Mail Templates</span></a>
            			</li>
            		</ul>
            </li>
        </ul>
    </div>
@endsection