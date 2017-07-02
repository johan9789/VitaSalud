@extends('layout')

@section('body')

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


{{ HTML::script("assets/lib/jquery/jquery.min.js") }}
{{ HTML::script("assets/lib/bootstrap/js/bootstrap.min.js") }}    

{{ HTML::script("assets/lib/datatables/jquery.dataTables.js") }}
{{ HTML::script("assets/lib/datatables/3/dataTables.bootstrap.js") }}

<!--Begin Datatables-->
          <div id="tabla-inventarios">
            <div id="load-inventarios">
            <div class="row">
              <div class="col-lg-12">
                <div class="box">
                  <header>
                    <div class="icons">
                      <i class="fa fa-table"></i>
                    </div>
                    @if(count($inventario) == 0)
                      <h5>Inventario Vacío</h5>                      
                    @else
                      <h5>Inventario</h5>
                    @endif
                    
                  </header>
                  <div id="mensaje">
                   
                  </div>
                  
                  <div id="collapse4" class="body">
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Detalle</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                      </thead>
                      <tbody id="inventario">
                          @foreach($inventario as $i)
                          <tr>
                              <td>{{ $j++}}</td>
                              <td>{{$i->NombreProductoDist}}</td>
                              <td>{{ $i->DetallesProductoDist }}</td>   
                              <td>{{ $i->stock }}</td>
                              <td>
                                @if($i->stock > 0)
                                <a href="#ver_detalle_inv" data-toggle="modal" class="btn btn-default dt-inv" data-id="{{md5($i->idProductoDist)}}" data-orden="1">
                                    <li id="icon_ver_cli_7" class="fa fa-eye"></li>
                                </a>
                                @else
                                <span class="label label-danger">Sin stock</span>
                                @endif
                              </td>                             
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <script>
              $(document).on('ready', function(){
                Metis.MetisTable();
        Metis.metisSortable();
              });

              </script>

            </div><!-- /.row -->

            </div>
          </div>
            <!--End Datatables-->

<span id="ur" name="{{ URL::to('gestion/inventario') }}"></span>
<span id="u" name="{{ URL::to('') }}"></span>

@stop