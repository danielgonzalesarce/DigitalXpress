@if ($paginator->hasPages())
    <nav aria-label="Navegación de páginas" class="pagination-wrapper">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
            {{-- Información de resultados --}}
            <div class="pagination-info">
                <p class="text-muted mb-0">
                    Mostrando 
                    <span class="fw-bold text-primary">{{ $paginator->firstItem() }}</span>
                    a 
                    <span class="fw-bold text-primary">{{ $paginator->lastItem() }}</span>
                    de 
                    <span class="fw-bold text-primary">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            {{-- Controles de paginación --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Botón Anterior --}}
                @if ($paginator->onFirstPage())
                    <button class="btn btn-pagination btn-pagination-disabled" disabled aria-label="Página anterior">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Anterior</span>
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-pagination" rel="prev" aria-label="Página anterior">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Anterior</span>
                    </a>
                @endif

                {{-- Números de página --}}
                <div class="pagination-numbers d-flex align-items-center gap-1">
                    @foreach ($elements as $element)
                        {{-- Separador "Three Dots" --}}
                        @if (is_string($element))
                            <span class="pagination-dots">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                        @endif

                        {{-- Array de enlaces --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="pagination-number pagination-number-active" aria-current="page">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="pagination-number" aria-label="Ir a la página {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Botón Siguiente --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-pagination" rel="next" aria-label="Página siguiente">
                        <span class="d-none d-sm-inline me-1">Siguiente</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button class="btn btn-pagination btn-pagination-disabled" disabled aria-label="Página siguiente">
                        <span class="d-none d-sm-inline me-1">Siguiente</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </div>
    </nav>

    <style>
        .pagination-wrapper {
            padding: 2rem 0;
            margin-top: 2rem;
        }

        .pagination-info {
            font-size: 0.95rem;
        }

        .pagination-info .fw-bold {
            color: var(--primary-color, #0d6efd) !important;
        }

        .btn-pagination {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color, #0d6efd);
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            min-width: 100px;
        }

        .btn-pagination:hover:not(.btn-pagination-disabled) {
            color: #ffffff;
            background-color: var(--primary-color, #0d6efd);
            border-color: var(--primary-color, #0d6efd);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-pagination:active:not(.btn-pagination-disabled) {
            transform: translateY(0);
        }

        .btn-pagination-disabled {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #495057;
            background-color: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination-number:hover {
            color: var(--primary-color, #0d6efd);
            background-color: #f8f9fa;
            border-color: var(--primary-color, #0d6efd);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        }

        .pagination-number-active {
            color: #ffffff !important;
            background-color: var(--primary-color, #0d6efd) !important;
            border-color: var(--primary-color, #0d6efd) !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .pagination-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: #adb5bd;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination-wrapper {
                padding: 1.5rem 0;
            }

            .pagination-info {
                text-align: center;
                font-size: 0.85rem;
            }

            .pagination-numbers {
                gap: 0.25rem;
            }

            .pagination-number {
                min-width: 36px;
                height: 36px;
                font-size: 0.85rem;
                padding: 0.4rem 0.6rem;
            }

            .btn-pagination {
                min-width: 80px;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .pagination-wrapper > div {
                flex-direction: column;
                gap: 1.5rem !important;
            }

            .pagination-numbers {
                order: -1;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
@endif
