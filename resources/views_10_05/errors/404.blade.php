 
@include('layouts.head')

        <?php $current = 'Page Not Found'; ?>
            
        <main class="main_content error_page ">
            <div id="notfound">
				<div class="notfound">
                    <div class="notfound-404">
                        <h1>404</h1>
                        <h2>LOOKS LIKE YOU ARE LOST</h2>
                    </div>
                <a href="{{ url('/') }}" class="btn btn-primary text-uppercase link">go to homepage</a>
				</div>
			</div>
        </main>
    </body>