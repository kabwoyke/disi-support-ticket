<?php

use Livewire\Component;
use App\Models\Equipment;

new class extends Component
{
    public string $search = '';

    public function render()
    {
        $assets = Equipment::with('category')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->get();

        return view('components.dashboard.⚡manage-asset', ['assets' => $assets]);
    }

    public function deleteAsset($id): void
    {
        Equipment::findOrFail($id)->delete();
    }
};
?>

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Hardware & Asset Management</h1>
            <p class="text-sm text-base-content/70">Manage office equipment registered across ticket categories.</p>
        </div>
        <div>
            <button class="btn btn-primary gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Asset
            </button>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-base-100 border border-primary-200/50 rounded-box p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search asset or category..."
                class="input input-bordered w-full pl-10 input-sm sm:input-md"
            />
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="text-xs text-base-content/60 self-end sm:self-center">
            Total Items: <span class="font-bold text-primary">{{ count($assets) }}</span>
        </div>
    </div>

    <!-- Asset Table Card -->
    <div class="bg-base-100 border border-primary-200/50 rounded-box shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full text-left">
                <thead class="bg-base-200/50 text-xs text-base-content/70 uppercase">
                    <tr>

                        <th>Equipment Name</th>
                        <th>Ticket Category</th>
                        <th>Created Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200 text-sm">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-base-200/30 transition-colors">
                            
                            <td>
                                <div class="font-semibold text-base-content">{{ $asset->name }}</div>
                            </td>
                            <td>
                                <span class="badge badge-primary badge-outline font-medium text-xs">
                                    {{ $asset->category->category_name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="text-xs text-base-content/70 whitespace-nowrap">
                                {{ $asset->created_at ? $asset->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="text-right whitespace-nowrap space-x-1">
                                <button class="btn btn-ghost btn-xs text-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
</svg>

                                    View
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-base-content/60">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="font-medium">No equipment found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
