<!-- Navbar -->
	  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
		<div class="container">
		  <a href="{{ route('user.index') }}" class="navbar-brand">
			<img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
			<span class="brand-text font-weight-light">Employer</span>
		  </a>

		  <!-- Right navbar links -->
		  <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
			@if(session('Login') == null)
				<li class="nav-item dropdown">
				  <a class="nav-link" data-toggle="dropdown" href="#">
					<i class="fas fa-user-circle"></i>
				  </a>
				  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
					<form action="{{ route('user.login') }}" method="POST">
						@csrf
						<div class="form-group">
							<label for="exampleInputEmail1">Email</label>
							<input type="email" name="email" class="form-control form-control-sm">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input type="password" name="password" class="form-control form-control form-control-sm">
						</div>
						<button type="submit" class="btn btn-sm btn-block btn-primary">Login</button>
					</form>
				  </div>
				</li>
			@else
				<li class="nav-item">
					<a class="nav-link" href="{{ route('user.logout') }}">Log Out</a>
				</li>
			@endif
		  </ul>
		</div>
	  </nav>
	  <!-- /.navbar -->