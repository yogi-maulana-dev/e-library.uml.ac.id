<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCrudController extends Controller
{
    public const MODULES = [
        'books' => ['label' => 'Buku', 'model' => \App\Models\Book::class, 'search' => ['title', 'isbn'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'book-categories' => ['label' => 'Kategori Buku', 'model' => \App\Models\BookCategory::class, 'search' => ['name'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'authors' => ['label' => 'Penulis', 'model' => \App\Models\Author::class, 'search' => ['name'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'publishers' => ['label' => 'Penerbit', 'model' => \App\Models\Publisher::class, 'search' => ['name'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'shelves' => ['label' => 'Rak Buku', 'model' => \App\Models\Shelf::class, 'search' => ['name', 'code'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'users' => ['label' => 'User', 'model' => \App\Models\User::class, 'search' => ['name', 'email'], 'roles' => ['Super Admin', 'Librarian']],
        'roles' => ['label' => 'Role', 'model' => \Spatie\Permission\Models\Role::class, 'search' => ['name', 'guard_name'], 'roles' => ['Super Admin']],
        'permissions' => ['label' => 'Permission', 'model' => \Spatie\Permission\Models\Permission::class, 'search' => ['name', 'guard_name'], 'roles' => ['Super Admin']],
        'sliders' => ['label' => 'Slider', 'model' => \App\Models\Slider::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'faqs' => ['label' => 'FAQ', 'model' => \App\Models\FAQ::class, 'search' => ['question'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'testimonials' => ['label' => 'Testimoni', 'model' => \App\Models\Testimonial::class, 'search' => ['name', 'content'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'events' => ['label' => 'Event', 'model' => \App\Models\Event::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'news' => ['label' => 'Berita', 'model' => \App\Models\News::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'pages' => ['label' => 'Halaman', 'model' => \App\Models\Page::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'repositories' => ['label' => 'Repository', 'model' => \App\Models\Repository::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'ebooks' => ['label' => 'Ebook', 'model' => \App\Models\Ebook::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'galleries' => ['label' => 'Galeri', 'model' => \App\Models\Gallery::class, 'search' => ['title'], 'roles' => ['Super Admin', 'Librarian', 'Staff']],
        'borrowings' => ['label' => 'Peminjaman', 'model' => \App\Models\Borrowing::class, 'search' => ['status'], 'roles' => ['Super Admin', 'Librarian']],
        'fines' => ['label' => 'Denda', 'model' => \App\Models\Fine::class, 'search' => ['reason', 'status'], 'roles' => ['Super Admin', 'Librarian']],
        'settings' => ['label' => 'Setting', 'model' => \App\Models\Setting::class, 'search' => ['key', 'value'], 'roles' => ['Super Admin']],
    ];

    public function index(Request $request, string $module): View
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);
        $model = $config['model'];
        $query = $model::query();

        if ($request->filled('q')) {
            $query->where(function ($query) use ($config, $request): void {
                foreach ($config['search'] as $column) {
                    $query->orWhere($column, 'like', '%'.$request->q.'%');
                }
            });
        }

        return view('admin.crud.index', [
            'module' => $module,
            'config' => $config,
            'columns' => $this->columns($model),
            'items' => $query->latest()->paginate(12)->withQueryString(),
        ]);
    }

    public function create(Request $request, string $module): View
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);

        return view('admin.crud.form', [
            'module' => $module,
            'config' => $config,
            'columns' => $this->editableColumns($config['model']),
            'item' => new $config['model'],
            'options' => $this->relationOptions(),
        ]);
    }

    public function store(Request $request, string $module): RedirectResponse
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);
        $this->validatePayload($request, $config['model']);
        $data = $this->payload($request, $config['model']);
        $item = $config['model']::create($data);
        $this->log('create', $item, $request);

        return redirect()->route('admin.crud.index', $module)->with('status', $config['label'].' berhasil dibuat.');
    }

    public function edit(Request $request, string $module, string $id): View
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);

        return view('admin.crud.form', [
            'module' => $module,
            'config' => $config,
            'columns' => $this->editableColumns($config['model']),
            'item' => $config['model']::findOrFail($id),
            'options' => $this->relationOptions(),
        ]);
    }

    public function update(Request $request, string $module, string $id): RedirectResponse
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);
        $this->validatePayload($request, $config['model']);
        $item = $config['model']::findOrFail($id);
        $item->update($this->payload($request, $config['model'], $item));
        $this->log('update', $item, $request);

        return redirect()->route('admin.crud.index', $module)->with('status', $config['label'].' berhasil diperbarui.');
    }

    public function destroy(Request $request, string $module, string $id): RedirectResponse
    {
        $config = $this->module($module);
        $this->authorizeModule($request, $config);
        $item = $config['model']::findOrFail($id);
        $this->log('delete', $item, $request);
        $item->delete();

        return back()->with('status', $config['label'].' berhasil dihapus.');
    }

    private function module(string $module): array
    {
        abort_unless(isset(self::MODULES[$module]), 404);

        return self::MODULES[$module];
    }

    private function authorizeModule(Request $request, array $config): void
    {
        abort_unless($request->user()?->hasAnyRole($config['roles'] ?? []), 403);
    }

    private function columns(string $model): array
    {
        return collect(Schema::getColumnListing((new $model)->getTable()))
            ->reject(fn ($column) => in_array($column, ['password', 'remember_token', 'deleted_at'], true))
            ->take(8)
            ->values()
            ->all();
    }

    private function editableColumns(string $model): array
    {
        return collect(Schema::getColumnListing((new $model)->getTable()))
            ->reject(fn ($column) => in_array($column, [
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
                'email_verified_at',
                'remember_token',
                'created_by',
                'uploaded_by',
                'approved_by',
                'approved_at',
                'paid_by',
                'paid_at',
            ], true))
            ->values()
            ->all();
    }

    private function payload(Request $request, string $model, ?Model $item = null): array
    {
        $columns = $this->editableColumns($model);
        $data = $request->only($columns);

        foreach ($columns as $column) {
            if (Str::startsWith($column, 'is_')) {
                $data[$column] = $request->boolean($column);
            }
        }

        if (in_array('slug', $columns, true) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'] ?? $data['name'] ?? Str::random(8));
        }

        $tableColumns = Schema::getColumnListing((new $model)->getTable());

        if (in_array('created_by', $tableColumns, true)) {
            $data['created_by'] = $request->user()->id;
        }

        if (in_array('uploaded_by', $tableColumns, true)) {
            $data['uploaded_by'] = $request->user()->id;
        }

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        if (array_key_exists('guard_name', $data) && blank($data['guard_name'])) {
            $data['guard_name'] = 'web';
        }

        return collect($data)->reject(fn ($value) => $value === '')->all();
    }

    private function validatePayload(Request $request, string $model): void
    {
        $rules = [];

        foreach ($this->editableColumns($model) as $column) {
            if ($column === 'email') {
                $rules[$column] = ['nullable', 'email', 'max:255'];
            } elseif ($column === 'password') {
                $rules[$column] = ['nullable', 'string', 'min:12'];
            } elseif (Str::contains($column, ['file_path', 'image', 'cover', 'avatar', 'logo'])) {
                $rules[$column] = ['nullable', 'string', 'max:255', 'regex:/\A(?!.*\.\.)(?!\/)[A-Za-z0-9_\-\/.]+\z/'];
            } elseif (Str::startsWith($column, 'is_')) {
                $rules[$column] = ['nullable', 'boolean'];
            } elseif (Str::endsWith($column, '_count') || in_array($column, ['total_copies', 'available_copies', 'pages', 'publication_year', 'file_size', 'order', 'capacity', 'available_spots', 'days_overdue'], true)) {
                $rules[$column] = ['nullable', 'integer', 'min:0'];
            } elseif (Str::endsWith($column, '_id')) {
                $rules[$column] = ['nullable', 'uuid'];
            } else {
                $rules[$column] = ['nullable', 'string', 'max:5000'];
            }
        }

        try {
            $request->validate($rules);
        } catch (ValidationException $exception) {
            ActivityLog::create([
                'user_id' => $request->user()?->id,
                'action' => 'validation.failed',
                'subject_type' => $model,
                'description' => 'Admin CRUD validation failed.',
                'ip_address' => $request->ip(),
            ]);

            throw $exception;
        }
    }

    private function relationOptions(): array
    {
        return [
            'category_id' => \App\Models\BookCategory::orderBy('name')->pluck('name', 'id')->all(),
            'publisher_id' => \App\Models\Publisher::orderBy('name')->pluck('name', 'id')->all(),
            'shelf_id' => \App\Models\Shelf::orderBy('code')->pluck('code', 'id')->all(),
            'user_id' => \App\Models\User::orderBy('name')->pluck('name', 'id')->all(),
            'book_id' => \App\Models\Book::orderBy('title')->pluck('title', 'id')->all(),
            'created_by' => \App\Models\User::orderBy('name')->pluck('name', 'id')->all(),
            'uploaded_by' => \App\Models\User::orderBy('name')->pluck('name', 'id')->all(),
            'borrowing_id' => \App\Models\Borrowing::latest()->limit(50)->pluck('id', 'id')->all(),
        ];
    }

    private function log(string $action, Model $item, Request $request): void
    {
        ActivityLog::create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'subject_type' => $item::class,
            'subject_id' => $item->getKey(),
            'description' => ucfirst($action).' '.$item->getTable(),
            'ip_address' => $request->ip(),
        ]);
    }
}
