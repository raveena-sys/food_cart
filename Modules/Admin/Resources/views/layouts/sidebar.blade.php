<div class="overlay-screen" (click)="closeMenu()"></div>
<aside class="sidemenu" id="sidemenu">
    <div class="sidebar-wrapper">
        <ul id="sideSubMenu" class="nav flex-column sidenav">
            <li class="{{(Request::segment(2) == 'dashboard') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/dashboard') }}">
                    <span class="nav_icon"><i class="icon-dashboard"></i></span>
                    <span class="nav_title">Dashboard</span></a>
            </li>
            <li class="{{(Request::segment(2) == 'manage-store-master') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/manage-store/list') }}">
                    <span class="nav_icon"><i class="fa fa-users" ></i></span>
                    <span class="nav_title">Store</span></a>
            </li>
            <li class="{{(Request::segment(2) == 'orders') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/orders') }}">
                    <span class="nav_icon"><i class="fa fa-money"></i></span>
                    <span class="nav_title">Orders</span></a>
            </li>
            <li class="{{(Request::segment(2) == 'product') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/product/list') }}">
                    <span class="nav_icon"><i class="fa fa-shopping-cart"></i></span>
                    <span class="nav_title">Product</span></a>
            </li>
            <!-- class="{{(Request::segment(2) == 'manage-manager') ? 'active' : ''}}" -->
            <li>
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageMaster" aria-expanded="false" aria-controls="ManageMaster">
                    <span class="nav_icon"><i class="fa fa-tasks"></i></span>
                    <span class="nav_title">Category</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageMaster">
                    <!-- <li class="ripple-effect {{(Request::segment(2) == 'manage-topping') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/topping_master') }}">
                            <span>Topping Master</span>
                        </a>
                    </li> -->

                    <li class="ripple-effect {{(Request::segment(2) == 'manage-category') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-category') }}">
                            <span>Category</span>
                        </a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-sub-category') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-sub-category') }}">
                            <span>Sub Category</span>
                        </a>
                    </li>


                    <li class="ripple-effect {{(Request::segment(2) == 'manage-size-master') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-size-master') }}">
                            <span>Size Master</span>
                        </a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-drink-master') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-drink-master') }}">
                            <span>Drink Master</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li>
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageMaster2" aria-expanded="false" aria-controls="ManageMaster2">
                    <span class="nav_icon"><i class="icon-a-manage-jobs"></i></span>
                    <span class="nav_title">Topping Customisation</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageMaster2">


                    <li class="ripple-effect {{(Request::segment(2) == 'manage-pizza-crust') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-pizza-crust') }}">
                            <span>Pizza Crust</span>
                        </a>
                    </li>

                    <li class="ripple-effect {{(Request::segment(2) == 'manage-pizza-sauce') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-pizza-sauce') }}">
                            <span>Pizza Sauce</span>
                        </a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-pizza-size') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-pizza-size') }}">
                            <span>Pizza Size</span>
                        </a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-pizza-size') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-pizza-cheese') }}">
                            <span>Pizza Cheese</span>
                        </a>
                    </li>

                    <li class="ripple-effect {{(Request::segment(2) == 'manage-topping-dips') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-topping-dips') }}">
                            <span>Topping Dips</span>
                        </a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-topping-pizza') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-topping-pizza') }}">
                            <span>Topping Pizza</span>
                        </a>
                    </li>

                    <li class="ripple-effect {{(Request::segment(2) == 'manage-topping-wing-flavour') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-topping-wing-flavour') }}">
                            <span>Topping Wing Flavour</span>
                        </a>
                    </li>

                    <li class="ripple-effect {{(Request::segment(2) == 'manage-topping-donair-shawarma-mediterranean') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-topping-donair-shawarma-mediterranean') }}">
                            <span>Topping Donair Shawarma Mediterranean</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="{{(Request::segment(2) == 'manage-cms' || Request::segment(2) == 'edit-cms') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/manage-cms') }}" aria-expanded="false">
                    <span class="nav_icon"><i class="icon-a-manage-cms"></i> </span>
                    <span class="nav_title">Manage CMS</span>
                </a>
            </li>

            <!-- <li class="{{(Request::segment(2) == 'manage-company') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageCompanies" aria-expanded="false" aria-controls="ManageCompanies">
                    <span class="nav_icon"><i class="icon-apartment"></i></span>
                    <span class="nav_title">Manage Companies</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageCompanies">
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-company' && Request::segment(2) == 'lender') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-company/lender') }}"><span>Lender</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(2) == 'manage-company' && Request::segment(3) == 'appraiser') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-company/appraiser') }}"><span>Appraiser</span></a>
                    </li>
                </ul>
            </li>

         <li class="{{(Request::segment(2) == 'manage-manager') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#Managemanager" aria-expanded="false" aria-controls="Managemanager">
                    <span class="nav_icon"><i class="icon-manager"></i></span>
                    <span class="nav_title">Manage Manager</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="Managemanager">
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-manager' && Request::segment(2) == 'lender') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-manager/lender') }}"><span>Lender</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-manager' && Request::segment(2) == 'appraiser') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-manager/appraiser') }}"><span>Appraiser</span></a>
                    </li>
                </ul>
            </li>
             <li class="{{(Request::segment(2) == 'manage-employee') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageEmployees" aria-expanded="false" aria-controls="manageUser">
                    <span class="nav_icon"><i class="icon-a-employees"></i></span>
                    <span class="nav_title">Manage Employees</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageEmployees">
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-manager' && Request::segment(2) == 'lender') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-employee/lender') }}"><span>Lender</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-manager' && Request::segment(2) == 'appraiser') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-employee/appraiser') }}"><span>Appraiser</span></a>
                    </li>
                </ul>
            </li>
             <li class="{{(Request::segment(2) == 'manage-individual') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageIndividuals" aria-expanded="false" aria-controls="manageUser">
                    <span class="nav_icon"><i class="icon-a-user"></i></span>
                    <span class="nav_title">Manage Individuals</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageIndividuals">
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-individual' && Request::segment(2) == 'lender') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-individual/lender') }}"><span>Lender</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-individual' && Request::segment(2) == 'appraiser') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-individual/appraiser') }}"><span>Appraiser</span></a>
                    </li>
                </ul>
            </li>

            <li class="{{(Request::segment(2) == 'manage-job') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/manage-job') }}" aria-expanded="false">
                    <span class="nav_icon"><i class="icon-a-manage-jobs"></i> </span>
                    <span class="nav_title">Manage Jobs</span>
                </a>
            </li>


            <li class="{{(Request::segment(2) == 'my-earning') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/my-earning') }}" aria-expanded="false">
                    <span class="nav_icon"><i class="icon-my-earnings"></i> </span>
                    <span class="nav_title">My Earnings</span>
                </a>
            </li>
             <li class="{{(Request::segment(2) == 'manage-report') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" role="button" data-toggle="collapse" href="#ManageCMST" aria-expanded="false" aria-controls="manageUser">
                    <span class="nav_icon"><i class="icon-s-report"></i></span>
                    <span class="nav_title">Manage Reports</span>
                </a>
                <ul class="list-unstyled collapse in-collapse " data-parent="#sideSubMenu" id="ManageCMST">
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-report' && Request::segment(2) == 'total-revenue') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-report/total-revenue') }}"><span>Total Revenue</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-report' && Request::segment(2) == 'registered-companies') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-report/registered-companies') }}"><span>Registered Companies</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-report' && Request::segment(2) == 'registered-individuals') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-report/registered-individuals') }}"><span>Registered Individuals</span></a>
                    </li>
                    <li class="ripple-effect {{(Request::segment(1) == 'manage-report' && Request::segment(2) == 'total-jobs') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-report/total-jobs') }}"><span>Total Jobs</span></a>
                    </li>
                </ul>
            </li>
             <li class="{{(Request::segment(2) == 'manage-cms' || Request::segment(2) == 'edit-cms') ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/manage-cms') }}" aria-expanded="false">
                    <span class="nav_icon"><i class="icon-a-manage-cms"></i> </span>
                    <span class="nav_title">Manage CMS</span>
                </a>
            </li> -->
        </ul>
    </div>
</aside>
