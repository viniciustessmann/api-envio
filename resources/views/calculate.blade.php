{!! Form::open(['route' => 'calculate-request']) !!}

<div class="form-group">
    {!! Form::label('CEP de origem', 'CEP de origem') !!}
    {!! Form::text('origin', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('CEP de destino', 'CEP de destino') !!}
    {!! Form::text('destiny', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('Peso', 'Peso') !!}
    {!! Form::number('peso', null, ['class' => 'form-control', 'required' => 'required', 'step' => 'any']) !!}
</div>

{!! Form::submit('Calcular', ['class' => 'btn btn-info']) !!}

{!! Form::close() !!}