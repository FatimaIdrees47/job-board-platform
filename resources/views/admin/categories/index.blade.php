@extends('layouts.dashboard')
@section('title', 'Category Management')
@section('sidebar')
    @include('admin._sidebar')
@endsection

@section('content')

<div x-data="{
    editModal: false,
    editId: null,
    editName: '',
    editIcon: '',
    editColor: '#7B5EA7',
    openEdit(id, name, icon, color) {
        this.editId = id;
        this.editName = name;
        this.editIcon = icon;
        this.editColor = color;
        this.editModal = true;
    }
}">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
        <div>
            <h2 style="margin-bottom:4px;">Categories</h2>
            <p style="font-size:13px;">{{ $categories->count() }} categories</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

        {{-- Category list --}}
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($categories as $category)
                <div class="card" style="padding:16px 20px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                        <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                            <div style="font-size:24px;flex-shrink:0;">{{ $category->icon }}</div>
                            <div>
                                <div style="font-size:14px;font-weight:500;color:var(--text-primary);">
                                    {{ $category->name }}
                                </div>
                                <div style="font-size:12px;color:var(--text-tertiary);">
                                    {{ $category->jobs_count }} {{ Str::plural('job', $category->jobs_count) }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;flex-shrink:0;">
                            <button
                                @click="openEdit({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->icon }}', '{{ $category->color ?? '#7B5EA7' }}')"
                                class="btn btn-ghost btn-sm">
                                Edit
                            </button>

                            @if($category->jobs_count === 0)
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Add category --}}
        <div class="card" style="padding:24px;position:sticky;top:80px;">
            <h4 style="margin-bottom:16px;">Add New Category</h4>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input"
                           placeholder="e.g. Machine Learning" required>
                </div>
                <div style="margin-bottom:12px;">
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" class="form-input"
                           placeholder="🤖" maxlength="4">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label">Color</label>
                    <input type="color" name="color" value="#7B5EA7"
                           style="width:100%;height:40px;border-radius:var(--radius-md);
                                  border:1px solid var(--bg-muted);background:var(--bg-subtle);
                                  cursor:pointer;padding:2px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Create Category
                </button>
            </form>
        </div>

    </div>

    {{-- Edit Modal --}}
    <div x-show="editModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:100;
                display:flex;align-items:center;justify-content:center;padding:24px;"
         @click.self="editModal = false">

        <div style="background:var(--bg-surface);border:1px solid var(--bg-muted);
                    border-radius:var(--radius-lg);padding:32px;width:100%;max-width:440px;
                    box-shadow:var(--shadow-card);"
             @click.stop>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                <h3 style="margin:0;">Edit Category</h3>
                <button @click="editModal = false"
                        style="background:none;border:none;color:var(--text-tertiary);
                               cursor:pointer;font-size:20px;line-height:1;padding:4px;">
                    ✕
                </button>
            </div>

            <form method="POST"
                  :action="'/admin/categories/' + editId"
                  style="display:flex;flex-direction:column;gap:16px;">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input"
                           x-model="editName" required>
                </div>

                <div>
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" class="form-input"
                           x-model="editIcon" maxlength="4">
                </div>

                <div>
                    <label class="form-label">Color</label>
                    <input type="color" name="color"
                           x-model="editColor"
                           style="width:100%;height:40px;border-radius:var(--radius-md);
                                  border:1px solid var(--bg-muted);cursor:pointer;padding:2px;">
                </div>

                <div style="display:flex;gap:10px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                        Save Changes
                    </button>
                    <button type="button" @click="editModal = false" class="btn btn-ghost" style="flex:1;justify-content:center;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection