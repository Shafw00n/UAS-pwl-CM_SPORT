@extends('layouts.master')

@section('title', $promo->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2">
                <li class="text-gray-500">/</li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-700">{{ $promo->title }}</li>
            </ol>
        </nav>

        <!-- Promo Header -->
        <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3">{{ $promo->title }}</h1>
                    <p class="text-red-100 text-lg mb-4">{{ $promo->description }}</p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>{{ $promo->produk->count() }} Produk</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-red-600 px-6 py-3 rounded-full font-bold text-lg shadow-lg">
                    {{ $promo->status }}
                </div>
            </div>
        </div>

        <!-- Filter & Sort (Optional) -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Semua Produk Promo</h2>
            <div class="text-gray-600">
                Menampilkan {{ $promo->produk->count() }} produk
            </div>
        </div>

        <!-- Produk Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            @foreach($promo->produk as $produk)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="relative">
                    @if($produk->gambar)
                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    
                    @php
                        $originalPrice = $produk->harga;
                        $promoPrice = $produk->pivot->promo_price;
                        $discount = $originalPrice > 0 ? round((($originalPrice - $promoPrice) / $originalPrice) * 100) : 0;
                    @endphp
                    
                    @if($discount > 0)
                    <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                        -{{ $discount }}%
                    </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $produk->nama_produk }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $produk->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                    
                    <div class="space-y-2 mb-4">
                        <div class="text-sm text-gray-400 line-through">
                            Rp {{ number_format($originalPrice, 0, ',', '.') }}
                        </div>
                        <div class="text-xl font-bold text-green-600">
                            Rp {{ number_format($promoPrice, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-green-600 font-medium">
                            Hemat Rp {{ number_format($originalPrice - $promoPrice, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    @if($produk->pivot->stock)
                    <div class="mb-3 text-sm">
                        <span class="text-gray-600">Stok Promo:</span>
                        <span class="font-semibold text-gray-800">{{ $produk->pivot->stock }}</span>
                    </div>
                    @endif
                    
                    <a href="{{ route('produk.show', $produk->pivot->produk_id) }}">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('promo.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Promo
            </a>
        </div>
    </div>
</div>
@endsection