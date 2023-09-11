@extends('layouts.admin')

@section('title', "Category")
    
@section('content')
<div class="flex row item-center gap-40">
    @foreach ($categories as $i => $category)
        <div class="h-150 ratio-1-1 rounded shadow bg-white flex column centerize relative">
            <img src="{{ asset('storage/category_icons/' . $category->icon) }}" alt="{{ $category->name }}" class="h-50 ratio-1-1">
            <div class="text center mt-1">
                {{ $category->name }}
            </div>

            <div class="absolute right-0 left-0 hover-to-show flex row gap-10 justify-center" style="bottom: -15px;">
                <div class="h-30 ratio-1-1 rounded-max bg-green pointer flex centerize" onclick="edit('{{ $category }}')">
                    <i class="bx bx-edit"></i>
                </div>
                <div class="h-30 ratio-1-1 rounded-max bg-red pointer flex centerize" onclick="del('{{ $category }}')">
                    <i class="bx bx-trash"></i>
                </div>
            </div>

            <div class="absolute top-50 flex row gap-10 hover-to-show" style="width: 120%;">
                @if ($i > 0)
                    <a href="{{ route('category.priority', [$category->id, 'increase']) }}" class="h-30 ratio-1-1 rounded-max bg-primary pointer flex centerize">
                        <
                    </a>
                @endif
                <div class="flex grow-1"></div>
                @if ($i != count($categories) - 1)
                    <a href="{{ route('category.priority', [$category->id, 'decrease']) }}" class="h-30 ratio-1-1 rounded-max bg-primary pointer flex centerize">
                        >
                    </a>
                @endif
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
            Tambah Kategori
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('category.create') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="flex row item-center gap-20">
                <div class="flex grow-1 column">
                    <div class="text size-18 bold">Upload Icon</div>
                    <div class="text muted size-12 w-80 mt-05">Pilih file png/svg, icon akan ditampilkan sebagai tampilan grid</div>
                </div>
                <div class="group">
                    <div class="h-80 ratio-1-1 rounded flex centerize border group" id="iconPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="icon" onchange="inputFile(this, '#add #iconPreview')" required>
                </div>
            </div>

            <div class="flex row item-center gap-20">
                <div class="flex grow-1 column">
                    <div class="text size-18 bold">Upload Cover</div>
                    <div class="text muted size-12 mt-05">Pilih file jpg/png, cover akan ditampilkan ketika user membuka halaman kategori</div>
                </div>
                <div class="group">
                    <div class="h-100 ratio-16-9 rounded flex centerize border group" id="coverPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="cover" onchange="inputFile(this, '#add #coverPreview')" required>
                </div>
            </div>

            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Nama</label>
            </div>

            <button class="primary w-100 mt-3">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="edit">
    <div class="modal-body">
        <div class="modal-title">
            Edit Kategori
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('category.update') }}" class="modal-content" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id">
            <div class="flex row item-center gap-20">
                <div class="flex grow-1 column">
                    <div class="text size-18 bold">Icon</div>
                    <div class="text muted size-12 w-80 mt-05">Pilih file png/svg, icon akan ditampilkan sebagai tampilan grid</div>
                </div>
                <div class="group">
                    <div class="h-80 ratio-1-1 rounded flex centerize border group" id="iconPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="icon" onchange="inputFile(this, '#edit #iconPreview')">
                </div>
            </div>

            <div class="flex row item-center gap-20">
                <div class="flex grow-1 column">
                    <div class="text size-18 bold">Cover</div>
                    <div class="text muted size-12 mt-05">Pilih file jpg/png, cover akan ditampilkan ketika user membuka halaman kategori</div>
                </div>
                <div class="group">
                    <div class="h-100 ratio-16-9 rounded flex centerize border group" id="coverPreview">
                        <i class="bx bx-upload text size-24"></i>
                    </div>
                    <input type="file" name="cover" onchange="inputFile(this, '#edit #coverPreview')">
                </div>
            </div>

            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Nama</label>
            </div>

            <button class="primary w-100 mt-3">Submit</button>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus kategori?
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('category.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus kategori <span id="name"></span>? Tindakan ini tidak dapat dibatalkan</div>

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
    const edit = data => {
        data = JSON.parse(data);
        modal("#edit").show();
        select("#edit #id").value = data.id;
        select("#edit #name").value = data.name;
        select("#edit #iconPreview").setAttribute('bg-image', `/storage/category_icons/${data.icon}`);
        select("#edit #iconPreview").innerHTML = "";
        select("#edit #coverPreview").setAttribute('bg-image', `/storage/category_covers/${data.cover}`);
        select("#edit #coverPreview").innerHTML = "";

        bindDivWithImage();
    }
    const del = data => {
        data = JSON.parse(data);
        modal("#delete").show();
        select("#delete #id").value = data.id;
        select("#delete #name").innerText = data.name;
    }
</script>
@endsection