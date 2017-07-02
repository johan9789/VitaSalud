<ul id="menu" class="bg-blue dker">
	<li class="nav-header">Menu</li>
    <li class="nav-divider"></li>
    <li class="active">
        <a href="{{ URL::to('') }}">
            <i class="fa fa-desktop"></i>
            <span class="link-title">&nbsp;Inicio</span> 
        </a> 
    </li>    
    <li class="nav-divider"></li>  
    @if(!Session::has('usuario'))    
    <li>
        <a href="{{ URL::to('login') }}">
            <i class="fa fa-sign-in"></i>
            <span class="link-title">Iniciar sesión</span> 
        </a> 
    </li>
    @else
	    @foreach($accesos as $acceso)
		    @if(is_null($acceso->url_acceso) || empty($acceso->url_acceso))
			<li>
			    <a href="javascript:;" title="{{{ $acceso->descripcion_acceso }}}">
			        <i class="{{{ $acceso->icono_acceso }}}"></i>
			        <span class="link-title">{{{ $acceso->nombre_acceso }}}</span> 
			        <span class="fa arrow"></span>
			    </a>
			    <ul>
			    	@foreach($sub_accesos($acceso->id_acceso) as $sub_acceso)
				    	@if(is_null($sub_acceso->url_acceso) || empty($sub_acceso->url_acceso))
				    	<li>                
			                <a href="javascript:;" title="{{{ $sub_acceso->descripcion_acceso }}}">
			                	{{{ $sub_acceso->nombre_acceso }}}  <span class="fa arrow"></span>  
			            	</a>
			                <ul>
			                	@foreach($sub_accesos($sub_acceso->id_acceso) as $mini_acceso)
			                    <li>{{ HTML::link($mini_acceso->url_acceso, $mini_acceso->nombre_acceso, ['title' => $mini_acceso->descripcion_acceso]) }}</li>
			                    @endforeach
			                </ul>
			            </li>
				    	@else
				    	<li>
			                {{ HTML::link($sub_acceso->url_acceso, $sub_acceso->nombre_acceso, ['title' => $sub_acceso->descripcion_acceso]) }}
			                <ul></ul>
			            </li>
				    	@endif
			    	@endforeach
			    </ul>
			</li>
		    @else
		    <li>
			    <a href="{{ $acceso->url_acceso }}" title="{{{ $acceso->descripcion_acceso }}}">
			        <i class="{{{ $acceso->icono_acceso }}}"></i>
			        <span class="link-title">{{{ $acceso->nombre_acceso }}}</span> 
			    </a>
			</li>
		    @endif
	    @endforeach
    @endif
    @if(Session::has('usuario'))
    <li>
        <a href="#" onclick="javascript:href='{{ URL::to('logout') }}'">
            <i class="fa fa-sign-in"></i>
            <span class="link-title">Salir</span> 
        </a> 
    </li>
    @endif
</ul>