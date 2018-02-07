{!! '@' . 'extends(\'app.layout.app\')' !!}
{!! '@' . 'section(\'content\')' !!}

    @{!! Form::open(['url' => route('')]) !!}
        @foreach($fields as $field)

        @include('fields.text', ['field' => $field])

        @endforeach

        <button type="submit" class="btn btn-primary">Save</button>
    @{!! Form::close() !!}

{!! '@' . 'endsection' !!}