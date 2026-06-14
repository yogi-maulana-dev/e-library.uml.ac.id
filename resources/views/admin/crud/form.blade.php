@extends('layouts.admin')
@section('title', $config['label'])
@section('content')
<h1 class="text-3xl font-bold">{{ $item->exists ? 'Edit' : 'Tambah' }} {{ $config['label'] }}</h1>
<form method="POST" action="{{ $item->exists ? route('admin.crud.update', [$module, $item->getKey()]) : route('admin.crud.store', $module) }}" class="mt-6 grid gap-4 rounded-md border bg-white p-5 md:grid-cols-2">
    @csrf
    @if($item->exists) @method('PUT') @endif
    @foreach($columns as $column)
        @php($value = old($column, $item->{$column}))
        <label class="block">
            <span class="text-sm font-semibold capitalize">{{ str_replace('_', ' ', $column) }}</span>
            @if(isset($options[$column]))
                <select name="{{ $column }}" class="mt-1 w-full rounded-md border-slate-200">
                    <option value="">Pilih</option>
                    @foreach($options[$column] as $key => $label)
                        <option value="{{ $key }}" @selected((string) $value === (string) $key)>{{ $label }}</option>
                    @endforeach
                </select>
            @elseif(str_starts_with($column, 'is_'))
                <select name="{{ $column }}" class="mt-1 w-full rounded-md border-slate-200">
                    <option value="1" @selected((bool) $value)>Ya</option>
                    <option value="0" @selected(!$value)>Tidak</option>
                </select>
            @elseif(str_contains($column, 'content') || str_contains($column, 'description') || str_contains($column, 'answer') || str_contains($column, 'notes') || str_contains($column, 'message'))
                <textarea name="{{ $column }}" rows="4" class="mt-1 w-full rounded-md border-slate-200">{{ $value }}</textarea>
            @elseif(str_contains($column, 'date') || str_ends_with($column, '_at'))
                <input name="{{ $column }}" value="{{ $value }}" type="datetime-local" class="mt-1 w-full rounded-md border-slate-200">
            @elseif($column === 'password')
                <input name="{{ $column }}" type="password" class="mt-1 w-full rounded-md border-slate-200" placeholder="Kosongkan jika tidak diganti">
            @else
                <input name="{{ $column }}" value="{{ $value }}" class="mt-1 w-full rounded-md border-slate-200">
            @endif
        </label>
    @endforeach
    <div class="md:col-span-2 flex gap-3">
        <button class="rounded-md bg-[#006633] px-5 py-3 font-semibold text-white">Simpan</button>
        <a href="{{ route('admin.crud.index', $module) }}" class="rounded-md border px-5 py-3 font-semibold">Batal</a>
    </div>
</form>
@endsection
