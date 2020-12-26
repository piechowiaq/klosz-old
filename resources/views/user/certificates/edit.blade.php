@extends('layouts.app')
@section('title', 'Certificates')
@section('content')
    @include('user.nav')
    @php
        /**
        * @var App\Company $company
        * @var App\Training $training
        * @var App\Certificate $certificate
        */
    @endphp
    <div class="md:w-5/6 md:m-0 m-2">
        <div>
            <div><h1 class="text-lg font-semibold pb-2">Edytuj Dyplom</h1></div>
        </div>
        <hr>
        <div>
            <form
                action="{{route('user.certificates.update', ['certificate' => $certificate, 'company'=>$company, 'training'=> $training])}}"
                method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @include('user.certificates.form')
                <button type="submit"
                        class="p-2 bg-transparent hover:bg-red-500 text-black-700  hover:text-white border text-indigo-500 hover:border-transparent rounded mt-4 ">
                    Edytuj Dyplom
                </button>
                @include('errors')
            </form>
        </div>
    </div>
@endsection
