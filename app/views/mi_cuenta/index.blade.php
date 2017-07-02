@extends('layout')

@section('body')

<div id="tb_com">
    <div id="tb_com_act">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <header>
                        <div class="icons"><i class="fa fa-table"></i></div>
                        <h5>Mis comisiones</h5>
                    </header>
                    <div id="collapse4" class="body">
                        <table id="data_table_comisiones" class="table table-bordered table-condensed table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha de Comisión</th>
                                    <th>Cantidad de Comisión</th>
                                    <th>Comentarios</th>   
                                    <th>Opción</th>                         
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comisiones as $com)
                                <tr id="row_{{ $com->idComisiones }}">
                                    <td>{{ date('d/m/Y', strtotime($com->FechaComision)) }}</td>
                                    <td>{{ $com->CantidadComision }}</td>
                                    <td>{{ $com->Comentarios }}</td>                                                    
                                    <td>
                                        {{ HTML::link('#', 'Eliminar', ['onclick' => 'eliminar_comision('.$com->idComisiones.');']) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{ Form::hidden('', Request::url(), ['id' => 'url_actual']) }}
{{ Form::hidden('', URL::to('mi-cuenta/eliminar'), ['id' => 'url_eliminar_comision']) }}

@stop

@section('resources')
{{ HTML::script('assets/own/js/mi_cuenta/index.js') }}
@stop