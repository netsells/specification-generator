        <div class="form-group">
            <label>{{ $field->getName() }}</label>
            @{!! Form::text('{{ $field->getName() }}', null, ['class' => 'form-control']) !!}
        </div>