@extends('layouts.admin')

@section('title', "Packages")
    
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
                <th style="width: 30%"></th>
                @foreach ($packages as $package)
                    <th>{{ $package->name }}</th>
                @endforeach
                <th>
                    <button class="small primary" onclick="modal('#add').show()">Tambah</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Price Monthly</td>
                @foreach ($packages as $package)
                    <td>{{ $package->price_monthly }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Price Annual</td>
                @foreach ($packages as $package)
                    <td>{{ $package->price_yearly }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Commission Fee</td>
                @foreach ($packages as $package)
                    <td>{{ $package->commission_fee }}%</td>
                @endforeach
            </tr>
            <tr>
                <td>Max Team Members</td>
                @foreach ($packages as $package)
                    <td>{{ $package->max_team_members }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Download Report Ability</td>
                @foreach ($packages as $package)
                    <td>
                        <div class="h-30 ratio-1-1 rounded-max flex centerize text size-20 {{ $package->download_report_ability ? 'bg-green' : 'bg-red' }}">
                            {!! $package->download_report_ability ? '<i class="bx bx-check"></i>' : '<i class="bx bx-x"></i>' !!}
                        </div>
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Maximum Upload Size</td>
                @foreach ($packages as $package)
                    <td>{{ $package->max_file_size }} bytes</td>
                @endforeach
            </tr>
            <tr>
                <td></td>
                @foreach ($packages as $package)
                    <td>
                        <button class="small blue" onclick="edit('{{ $package }}')">
                            Edit
                        </button>
                        <br />
                        <button class="small red mt-1" onclick="del('{{ $package }}')">
                            Hapus
                        </button>
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div class="modal" id="add">
    <div class="modal-body">
        <div class="modal-title">
            Tambah Package Baru
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('package.create') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="download_report_ability" id="download_report_ability" value="0">
            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Name</label>
            </div>
            <div class="group">
                <textarea name="description" id="description" required></textarea>
                <label for="description">Description</label>
            </div>
            
            <div class="flex row gap-20 item-center">
                <div class="group flex column grow-1">
                    <input type="number" id="price_monthly" name="price_monthly" required>
                    <label class="active" for="price_monthly">Harga bulanan</label>
                </div>
                <div class="group flex column grow-1">
                    <input type="number" id="price_yearly" name="price_yearly" required>
                    <label class="active" for="price_yearly">Harga tahunan</label>
                </div>
            </div>

            <div class="group">
                <input type="number" id="commission_fee" name="commission_fee" min="1" required>
                <label for="commission_fee">Commission Fee (%)</label>
            </div>
            <div class="group">
                <input type="number" id="max_team_members" name="max_team_members" min="1" required>
                <label for="max_team_members">Anggota Tim Maksimal</label>
            </div>
            <div class="group">
                <input type="number" id="max_file_size" name="max_file_size" min="1" required>
                <label for="max_file_size">Ukuran File Upload Maksimal</label>
            </div>

            <div class="flex row item-center gap-10 mt-2">
                <div class="text flex grow-1">Bisa download report ke excel</div>
                <div class="switch" id="download_report_ability_switch" whenOn="() => {
                    select('#add #download_report_ability').value = 1;
                }" whenOff="() => {
                    select('#add #download_report_ability').value = 0;
                }">
                    <div></div>
                </div>
            </div>

            <button class="w-100 mt-3 primary">Tambahkan</button>
        </form>
    </div>
</div>

<div class="modal" id="edit">
    <div class="modal-body">
        <div class="modal-title">
            Edit <span id="name_display"></span> Package
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('package.update') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="download_report_ability" name="download_report_ability">

            <div class="group">
                <input type="text" id="name" name="name" required>
                <label for="name">Name</label>
            </div>
            <div class="group">
                <textarea name="description" id="description" required></textarea>
                <label for="description">Description</label>
            </div>

            <div class="flex row gap-20 item-center">
                <div class="group flex column grow-1">
                    <input type="number" id="price_monthly" name="price_monthly" required>
                    <label for="price_monthly">Harga bulanan</label>
                </div>
                <div class="group flex column grow-1">
                    <input type="number" id="price_yearly" name="price_yearly" required>
                    <label for="price_yearly">Harga tahunan</label>
                </div>
            </div>

            <div class="group">
                <input type="number" id="commission_fee" name="commission_fee" min="1" required>
                <label for="commission_fee">Commission Fee (%)</label>
            </div>
            <div class="group">
                <input type="number" id="max_team_members" name="max_team_members" min="1" required>
                <label for="max_team_members">Anggota Tim Maksimal</label>
            </div>
            <div class="group">
                <input type="number" id="max_file_size" name="max_file_size" min="1" required>
                <label for="max_file_size">Ukuran File Upload Maksimal</label>
            </div>

            <div class="flex row item-center gap-10 mt-2">
                <div class="text flex grow-1">Bisa download report ke excel</div>
                <div class="switch" id="download_report_ability_switch" whenOn="() => {
                    select('#edit #download_report_ability').value = 1;
                }" whenOff="() => {
                    select('#edit #download_report_ability').value = 0;
                }">
                    <div></div>
                </div>
            </div>

            <div class="mt-3 flex row item-center justify-end gap-20">
                <div class="pointer text muted" onclick="modal('#edit').hide()">Batal</div>
                <div class="pointer text red bold" onclick="select('#edit form').submit()">Simpan Perubahan</div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="delete">
    <div class="modal-body">
        <div class="modal-title">
            Hapus Package?
            <span class="bx bx-x modal-close" hide></span>
        </div>
        <form action="{{ route('package.delete') }}" class="modal-content" method="POST">
            {{ csrf_field() }}
            <input type="hidden" id="id" name="id">
            <div class="text size-14">Yakin ingin menghapus package <span id="name"></span>? Tindakan ini akan menurunkan package semua pengguna yang menggunakannya menjadi package dengan harga terdekat dibawah package ini</div>

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
        select("#edit #name_display").innerText = data.name;
        select("#edit #name").value = data.name;
        select("#edit #description").value = data.description;
        select("#edit #price_yearly").value = data.price_yearly;
        select("#edit #price_monthly").value = data.price_monthly;
        select("#edit #max_team_members").value = data.max_team_members;
        select("#edit #max_file_size").value = data.max_file_size;
        select("#edit #commission_fee").value = data.commission_fee;

        let downloadAbilitySwitch = select("#edit #download_report_ability_switch");
        if (data.download_report_ability) {
            downloadAbilitySwitch.classList.add('on');
            select('#download_report_ability').value = 1;
        } else {
            downloadAbilitySwitch.classList.remove('on');
            select('#download_report_ability').value = 0;
        }
    }
    const del = data => {
        data = JSON.parse(data);
        modal("#delete").show();
        select("#delete #id").value = data.id;
    }
</script>
@endsection