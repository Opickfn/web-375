{{-- resources/views/components/sort-icon.blade.php --}}
@if($sortBy === $col)
    @if($sortDir === 'asc')
        <i data-lucide="chevron-up" style="width:12px;height:12px;display:inline;vertical-align:middle;margin-left:3px;color:#5588A3;"></i>
    @else
        <i data-lucide="chevron-down" style="width:12px;height:12px;display:inline;vertical-align:middle;margin-left:3px;color:#5588A3;"></i>
    @endif
@else
    <i data-lucide="chevrons-up-down" style="width:12px;height:12px;display:inline;vertical-align:middle;margin-left:3px;color:rgba(232,232,232,0.2);"></i>
@endif