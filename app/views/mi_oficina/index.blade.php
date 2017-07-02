@extends('layout')

@section('body')
<div class="col-lg-8">
<div class="box">
    <header>
        <h5>Mi Oficina</h5>
    </header>
    <div class="body">
        <div class="row">
            <div class="col-lg-6">
                <div class="well well-sm">
                    <div id="add-event-form">
                        <fieldset>
                          <legend>Volumen Personal</legend>
                            <center>
                            <h1>0</h1>
                            </center>
                          <br>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="well well-sm">
                    <div id="add-event-form">
                        <fieldset>
                          <legend>Volumen Grupal</legend>
                            <center>
                            <h1>0</h1>
                            </center>
                          <br>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="col-lg-4">
<div class="box">
    <header>
        <h5>Comisiones</h5>
    </header>
    <div class="body">
        <div class="row">
            <div class="col-lg-12">
                <div class="well well-sm">
                    <div id="add-event-form">
                        <fieldset>
                          <legend>Residuales del mes</legend>
                            <center>
                            <h1>S/. 0.00</h1>
                            </center>
                          <br>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="col-lg-12">
<div class="box">
    <header>
        <h5>Rangos</h5>
    </header>
    <div class="body">
        <div class="row">
            <div class="col-lg-6">
                <div class="well well-sm">
                    <div id="add-event-form">
                        <fieldset>
                          <legend>Rango Alcanzado</legend>
                            <center>
                            <h1>DIA</h1>
                            </center>
                          <br>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="well well-sm">
                    <div id="add-event-form">
                        <fieldset>
                          <legend>Siguiente Rango</legend>
                            <center>
                            <h1>Director</h1>
                            </center>
                          <br>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@stop