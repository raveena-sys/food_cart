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
                    <!-- <li class="ripple-effect {{(Request::segment(2) == 'manage-drink-master') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL::To('admin/manage-drink-master') }}">
                            <span>Drink Master</span>
                        </a>
                    </li> -->

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

            <li class="{{Request::segment(2) == 'manage-social' ? 'active' : ''}}">
                <a class="nav-link ripple-effect" href="{{ URL::To('admin/manage-social/edit') }}" aria-expanded="false">
                    <span class="nav_icon"><i class="fa fa-external-link-square"></i> </span>
                    <span class="nav_title">Manage Social Link</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
