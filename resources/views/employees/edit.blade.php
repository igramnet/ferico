@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Редактировать сотрудника</h1>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary mb-3">Назад к списку сотрудников</a>

                <div class="d-none alert alert-danger" id="error-messages"></div>
                <div class="d-none alert alert-success" id="ok-messages"></div>

                <form id="employeeForm" action="{{ route('employees.update.ajax', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="first_name">Имя</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Фамилия</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}">
                    </div>
                    <div class="form-group">
                        <label for="company_id">Компания</label>
                        <select class="form-control" id="company_id" name="company_id">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                    </div>
                    <div class="form-group">
                        <label for="note">Примечание</label>
                        <textarea class="form-control" id="note" name="note" rows="4">{{ old('note', $employee->note) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Обновить сотрудника</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function () {
            jQuery('#employeeForm').on('submit', function (e) {
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
                        window.location.href = '{{ route('employees.index') }}';
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
