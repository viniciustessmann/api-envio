{!! Form::open(['route' => 'calculate2']) !!}

<div class="form-group">
    {!! Form::label('CEP de origem', 'CEP de origem') !!}
    {!! Form::text('origin', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('CEP de destino', 'CEP de destino') !!}
    {!! Form::text('destiny', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Peso', 'Peso') !!}
    {!! Form::text('peso', null, ['class' => 'form-control']) !!}
</div>


{!! Form::submit('Calcular', ['class' => 'btn btn-info']) !!}

{!! Form::close() !!}