<div class="d-flex align-items-stretch">
  <!-- Sidebar Navigation-->
  <nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center">
      <div class="avatar"><img src="{{ asset('admincss/img/avatar-6.jpg') }}" alt="..." class="img-fluid rounded-circle"></div>
      <div class="title">
        <h1 class="h5">{{ ucfirst(Auth::user()->name) }}</h1>
        <p>ABC Store</p>
      </div>
    </div>
    <!-- Sidebar Navigation Menus-->
    <span class="heading">Main</span>
    <ul class="list-unstyled">
      <!-- Home: Accessible by all roles -->
      <li><a href="{{ url('admin/dashboard') }}"> <i class="icon-home"></i>Home </a></li>

      @if(Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'operationm')
        <!-- Category: Admin and Operation Manager -->
        <li><a href="{{ url('view_category') }}"> <i class="icon-home"></i>Category </a></li>

        <!-- Products: Admin and Operation Manager -->
        <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Products </a>
          <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
            <li><a href="{{ url('add_product') }}">Add Product</a></li>
            <li><a href="{{ url('view_product') }}">View Product</a></li>
          </ul>
        </li>
      @endif

      @if(Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'salesm')
        <!-- Orders: Admin and Sales Manager -->
        <li><a href="{{ url('view_orders') }}"> <i class="icon-grid"></i>Orders </a></li>
      @endif
    </ul>
  </nav>
