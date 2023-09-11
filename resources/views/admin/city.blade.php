@extends('layouts.admin')

@section('title', "Cities")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif

<div class="flex row gap-20 wrap">
    @foreach ($cities as $city)
        <div class="flex column grow-1 basis-4 bg-white rounded relative">
            <img 
                src="{{ asset('storage/city_covers/' . $city->cover) }}" alt="{{ $city->name }}"
                class="w-100 ratio-9-16 rounded cover"
            >
            <div class="absolute top-0 bottom-0 left-0 right-0 p-2 bg-black transparent rounded flex column justify-end">
                <div class="text size-20 white bold mb-1">{{ $city->name }}</div>
            </div>
            <div class="flex row gap-20 hover-to-show absolute top-0 right-0 left-0 p-2">
                <button class="blue small flex grow-1 justify-center gap-10" onclick="edit('{{ $city }}')">
                    <i class="bx bx-edit"></i>
                    Edit
                </button>
                <button class="red small flex grow-1 justify-center gap-10" onclick="del('{{ $city }}')">
                    <i class="bx bx-trash"></i>
                    Hapus
                </button>
            </div>
        </div>
    @endforeach
</div>

<button class="FAB primary" onclick="modal('#add').show()">
    <i class="bx bx-plus"></i>
</button>

<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Kota
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('city.create') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="flex row item-center gap-20">
                <div class="flex column grow-1">
                    <div class="text size-20 bold">Cover</div>
                    <div class="text size-12">Foto landmark kota yang akan ditambahkan.</div>
                </div>
                <div class="group">
                    <div class="h-150 ratio-9-16 rounded flex centerize border group" id="coverPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="cover" onchange="inputFile(this, '#add #coverPreview')" required>
                </div>
            </div>

            <div class="group">
                <input type="text" name="name" id="name" required>
                <label for="name">Nama kota</label>
            </div>

            <button class="w-100 primary mt-2">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="edit">
    <div class="modal-body">
        <div class="modal-title">
            Edit Kota
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('city.update') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="flex row item-center gap-20">
                <div class="flex column grow-1">
                    <div class="text size-20 bold">Cover</div>
                    <div class="text size-12">Foto landmark kota yang akan ditambahkan.</div>
                </div>
                <div class="group">
                    <div class="h-150 ratio-9-16 rounded flex centerize border group" id="coverPreview"></div>
                    <input type="file" name="cover" onchange="inputFile(this, '#edit #coverPreview')">
                </div>
            </div>

            <div class="group">
                <input type="text" name="name" id="name" required>
                <label for="name">Nama kota</label>
            </div>

            <button class="w-100 primary mt-2">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus kota?
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('city.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus kota <span id="name"></span>? Tindakan ini tidak dapat dibatalkan</div>

            <div class="mt-3 flex row item-center justify-end gap-20">
                <div class="pointer text muted" onclick="modal('#delete').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#delete form').submit()">Hapus</div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')
<script>
    const del = data => {
        data = JSON.parse(data);
        modal('#delete').show()
        select("#delete #id").value = data.id;
        select("#delete #name").innerHTML = data.name;
    }
    const edit = data => {
        data = JSON.parse(data);
        modal('#edit').show()
        select("#edit #id").value = data.id;
        select("#edit #name").value = data.name;
        select("#edit #coverPreview").setAttribute('bg-image', `/storage/city_covers/${data.cover}`);
        bindDivWithImage()
    }
</script>
@endsection