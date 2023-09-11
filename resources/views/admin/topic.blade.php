@extends('layouts.admin')

@section('title', "Topic")
    
@section('content')
@if ($message != "")
    <div class="bg-green rounded p-2 mb-2">
        {{ $message }}
    </div>
@endif
<div class="bg-white rounded p-4 border">
    <table>
        <thead>
            <tr>
                <th style="width: 80px"></th>
                <th>Topik</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topics as $topic)
                <tr>
                    <td>
                        <img src="{{ asset('storage/topic_icons/' . $topic->icon) }}" alt="{{ $topic->name }}" class="h-50 ratio-1-1 cover">
                    </td>
                    <td>{{ $topic->name }}</td>
                    <td>
                        <button class="small blue" onclick="edit('{{ $topic }}')">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="small red" onclick="del('{{ $topic }}')">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button class="FAB primary" onclick="modal('#add').show()">
    <i class="bx bx-plus"></i>
</button>

<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Topik
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('topic.create') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="flex row item-center gap-20">
                <div class="flex column grow-1">
                    <div class="text size-20 bold">Icon</div>
                    <div class="text size-12">Foto landmark kota yang akan ditambahkan.</div>
                </div>
                <div class="group">
                    <div class="h-100 ratio-1-1 rounded flex centerize border group" id="icon">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="icon" onchange="inputFile(this, '#add #icon')" required>
                </div>
            </div>
            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Topik :</label>
            </div>

            <button class="primary mt-3 w-100">Tambahkan</button>
        </form>
    </div>
</div>

<div class="modal" id="edit">
    <div class="modal-body">
        <div class="modal-title">
            Edit Topik
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('topic.update') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="flex row item-center gap-20">
                <div class="flex column grow-1">
                    <div class="text size-20 bold">Icon</div>
                    <div class="text size-12">Foto landmark kota yang akan ditambahkan.</div>
                </div>
                <div class="group">
                    <div class="h-100 ratio-1-1 rounded flex centerize border group" id="iconPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="icon" onchange="inputFile(this, '#edit #iconPreview')">
                </div>
            </div>
            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Topik :</label>
            </div>

            <button class="primary mt-3 w-100">Tambahkan</button>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus topik?
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('topic.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus topik <span id="name"></span>? Tindakan ini tidak dapat dibatalkan</div>

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
        select("#delete #name").innerHTML = data.name;
        select("#delete #id").value = data.id;
    }
    const edit = data => {
        data = JSON.parse(data);
        modal('#edit').show()
        select("#edit #name").value = data.name;
        select("#edit #id").value = data.id;
        select("#edit #iconPreview").setAttribute('bg-image', `/storage/topic_icons/${data.icon}`);
        bindDivWithImage();
    }
</script>
@endsection