@extends('layouts.app')

@section('title', 'Pago Exitoso - DigitalXpress')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header de Éxito -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pedido Procesado Exitosamente!</h1>
                <p class="text-lg text-gray-600">Tu orden ha sido confirmada y está siendo procesada</p>
            </div>

            <!-- Información de la Orden -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles de tu Orden</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Número de Orden</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Estado</h3>
                        <p class="mt-1 text-lg font-semibold text-yellow-600 capitalize">{{ $order->status }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($order->total, 2) }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Fecha</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Envío</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Cliente</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Teléfono</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Método de Pago</h3>
                        <p class="mt-1 text-lg text-gray-900 capitalize">{{ $order->payment_method }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Dirección de Envío</h3>
                    <p class="mt-1 text-lg text-gray-900">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Productos de la Orden -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Productos Ordenados</h2>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                            <img src="{{ $item->product->image_url ?? '/images/placeholder.jpg' }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 object-cover rounded-md">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }} c/u</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                ${{ number_format($item->quantity * $item->price, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Próximos Pasos -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-blue-900 mb-4">Próximos Pasos</h2>
                <ul class="space-y-2 text-blue-800">
                    <li>📧 Recibirás un email de confirmación en {{ $order->customer_email }}</li>
                    <li>📦 Tu pedido será preparado y enviado en 1-2 días hábiles</li>
                    <li>🚚 Recibirás un email con el número de seguimiento cuando sea enviado</li>
                    <li>📞 Si tienes preguntas, contacta nuestro soporte 24/7</li>
                </ul>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    🏠 Continuar Comprando
                </a>
                
                @if(Auth::check())
                    <a href="{{ route('profile.edit') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        👤 Ver Mi Perfil
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
