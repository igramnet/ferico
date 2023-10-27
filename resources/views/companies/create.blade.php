@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Создать компанию</h1>
                <a href="{{ route('companies.index') }}" class="btn btn-secondary mb-3">Назад к списку компаний</a>

                <div class="d-none alert alert-danger" id="error-messages"></div>
                <div class="d-none alert alert-success" id="ok-messages"></div>

                <form id="companyForm" action="{{ route('companies.store.ajax') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group">
                        <label for="website">Сайт</label>
                        <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}">
                    </div>
                    <div class="form-group">
                        <label for="logo">Путь к логотипу</label>
                        <input type="text" class="form-control" id="logo" name="logo" value="{{ old('logo') }}">
                    </div>
                    <div class="form-group">
                        <label for="note">Примечание</label>
                        <textarea class="form-control" id="note" name="note" rows="4">{{ old('note') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Создать компанию</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function () {
            jQuery('#companyForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = $(this).attr('action');
                let method = $(this).attr('method');
                $('#error-messages').hide();

                jQuery.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        window.location.href = '{{ route('companies.index') }}';
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            for (let field in errors) {
                                errorMessages.push(errors[field][0]);
                            }

                            $('#error-messages').removeClass('d-none').show();
                            $('#error-messages').html(errorMessages.join('<br>'));
                        }
                    }

                });
            });
        });
    </script>
@endsection
