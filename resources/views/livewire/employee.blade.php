<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success my-2" role="alert">
            {{ session('message') }}
        </div>
    @endif
    <!-- START FORM -->
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <form>
            <div class="mb-3 row">
                <label for="nama" class="col-sm-2 col-form-label">First name</label>
                <div class="col-sm-10">
                    <input type="text"
                        class="form-control @error('first')
                            is-invalid
                        @enderror"
                        @if ($showData) disabled @endif wire:model='first'>
                    @error('first')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="email" class="col-sm-2 col-form-label">Last name</label>
                <div class="col-sm-10">
                    <input type="text"
                        class="form-control
                        @error('last')
                            is-invalid
                        @enderror"
                        @if ($showData) disabled @endif wire:model='last'>
                    @error('last')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="" class="col-sm-2 col-form-label">Category</label>
                <div class="col-sm-10">
                    <select class="col-sm-10 form-select @error('category_id') is-invalid @enderror"
                        @if ($showData) disabled @endif aria-label="Default select example"
                        name="category_id" wire:model='category_id'>
                        <option selected>- Pilih Item -</option>
                        @foreach ($category as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    @if ($updateData == false && $showData == false)
                        <button type="button" class="btn btn-primary" name="submit"
                            wire:click='store()'>Submit</button>
                    @elseif($showData == true)
                    @else
                        <button type="button" class="btn btn-primary" name="submit"
                            wire:click='update()'>Update</button>
                    @endif
                    <button type="button" class="btn btn-secondary" name="submit" wire:click='clear()'>Clear</button>
                </div>
            </div>
        </form>
    </div>
    <!-- AKHIR FORM -->

    <!-- START DATA -->
    <h1 class="m-0">Data Pegawai</h1>
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="d-flex flex-row justify-content-between">
            <form wire:submit.prevent='updatePage'>
                <select wire:model='page' wire:change='updatePage' class="form-select form-select mb-3"
                    style="width: 70px">
                    <option value="2">2</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </form>
            <div class="d-flex flex-row gap-3">
                <div class="mb-3">
                    <form wire:submit.prevent='updatePage'>
                        <select class="form-select " aria-label="Default select example" wire:model='searchCategory'
                            wire:change='updatePage'>
                            <option selected value="">- Cari Item -</option>
                            @foreach ($category as $data)
                                <option value="{{ $data->name }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" aria-describedby="helpId" placeholder="Search"
                        wire:model.live='result' />
                </div>
            </div>


        </div>


        <table class="table table-striped table-sortable">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-3 sort @if ($sortType == 'asc' && $sortColumn == 'first') asc @else 'desc' @endif "
                        wire:click="sort('first')">First</th>
                    <th class="col-md-3 sort @if ($sortType == 'asc'&& $sortColumn == 'last') asc @else 'desc' @endif"
                        wire:click="sort('last')">Last</th>
                    <th class="col-md-3 sort @if ($sortType == 'asc'&& $sortColumn == 'category_id') asc @else 'desc' @endif"
                        wire:click="sort('category_id')">Category</th>
                    <th class="col-md-3 ">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employee as $key => $value)
                    <tr>
                        <td>{{ $employee->firstItem() + $key }}</td>
                        <td>{{ $value->first }}</td>
                        <td>{{ $value->last }}</td>
                        <td>{{ $value->category->name }}</td>

                        <td>
                            <a wire:click='show({{ $value->id }})'
                                class="btn btn-primary btn-sm @if ($updateData == true) disabled @endif">Show</a>
                            <a wire:click='edit({{ $value->id }})'
                                class="btn btn-warning btn-sm @if ($showData == true) disabled @endif">Edit</a>
                            <a wire:click='delete_confirmation({{ $value->id }})'
                                class="btn btn-danger btn-sm @if ($updateData == true || $showData == true) disabled @endif"
                                data-bs-toggle="modal" data-bs-target="#coba">Del</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <div class="d-flex d-row justify-content-end">
            {{ $employee->links() }}
        </div>

        <div wire:ignore.self class="modal fade" id="coba" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi delete</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah kamu yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button wire:click='delete()' type="button" class="btn btn-primary"
                            data-bs-dismiss="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
