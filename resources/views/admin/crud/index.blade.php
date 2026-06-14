@extends('layouts.admin')
@section('title', $config['label'])
@section('content')
<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-3xl font-bold">{{ $config['label'] }}</h1>
        <p class="text-sm text-slate-500">Kelola data {{ strtolower($config['label']) }}.</p>
    </div>
    <a href="{{ route('admin.crud.create', $module) }}" class="rounded-md bg-[#006633] px-4 py-2 text-sm font-semibold text-white">Tambah</a>
</div>
<form class="mt-5 flex gap-2">
    <input name="q" value="{{ request('q') }}" class="w-full rounded-md border-slate-200" placeholder="Search AJAX/Data">
    <button class="rounded-md border bg-white px-4">Cari</button>
</form>
<div class="mt-5 overflow-x-auto rounded-md border bg-white">
    <table class="w-full text-sm">
        <thead><tr class="border-b bg-slate-50 text-left">
            @foreach($columns as $column)<th class="px-3 py-2">{{ str_replace('_', ' ', $column) }}</th>@endforeach
            <th class="px-3 py-2">Aksi</th>
        </tr></thead>
        <tbody>
            @foreach($items as $item)
                <tr class="border-b">
                    @foreach($columns as $column)
                        <td class="max-w-64 truncate px-3 py-2">{{ is_bool($item->{$column}) ? ($item->{$column} ? 'Ya' : 'Tidak') : $item->{$column} }}</td>
                    @endforeach
                    <td class="flex gap-2 px-3 py-2">
                        <a class="rounded bg-amber-500 px-2 py-1 text-xs text-white" href="{{ route('admin.crud.edit', [$module, $item->getKey()]) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.crud.destroy', [$module, $item->getKey()]) }}" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="rounded bg-red-600 px-2 py-1 text-xs text-white">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $items->links() }}</div>
@endsection
