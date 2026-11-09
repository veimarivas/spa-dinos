@php
    $totalPeriodo = $gastosPorCategoria->sum('total');
@endphp
<div class="table-responsive">
    <table class="table table-modern table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Categoria</th>
                <th class="text-end">Total Bs</th>
                @if($totalPeriodo > 0)
                    <th class="text-center" style="width:200px;">% del periodo</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($gastosPorCategoria as $item)
                @php
                    $pct = $totalPeriodo > 0 ? ($item->total / $totalPeriodo) * 100 : 0;
                    $isChild = !is_null($item->parent_id);
                @endphp
                <tr class="{{ $isChild ? 'table-light' : '' }}">
                    <td>
                        @if($isChild)
                            <div class="d-flex align-items-center">
                                <div class="text-muted me-2" style="font-size:0.7rem;">
                                    <i class="ri-corner-down-right-line"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold small">{{ $item->nombre }}</span>
                                    <br>
                                    <small class="text-muted" style="font-size:0.65rem;">
                                        <i class="ri-price-tag-3-line me-1"></i>{{ $item->parent_nombre }}
                                    </small>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-danger bg-opacity-10 text-danger rounded-circle">
                                            <i class="ri-price-tag-3-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="fw-bold">{{ $item->nombre }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="text-end fw-semibold {{ $isChild ? '' : 'text-danger' }}">
                        {{ number_format($item->total, 2) }}
                    </td>
                    @if($totalPeriodo > 0)
                        <td class="text-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:{{ $isChild ? '4px' : '8px' }};border-radius:{{ $isChild ? '2px' : '4px' }};">
                                    <div class="progress-bar {{ $isChild ? 'bg-secondary' : 'bg-danger' }}" role="progressbar"
                                        style="width:{{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted fw-semibold" style="min-width:38px;font-size:{{ $isChild ? '0.65rem' : '0.75rem' }};">{{ number_format($pct, 1) }}%</small>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">
                        <i class="ri-inbox-line me-1"></i>No hay gastos en este periodo
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th class="fw-bold">
                    <i class="ri-calculator-line me-1"></i>Total del periodo
                </th>
                <th class="text-end fw-bold text-danger fs-5">
                    {{ number_format($totalPeriodo, 2) }} Bs
                </th>
                @if($totalPeriodo > 0)
                    <th></th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>
