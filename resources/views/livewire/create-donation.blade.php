<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-amber-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Logo e Header -->
        <div class="text-center mb-10 animate-fade-in">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Solidyficando Vidas" class="h-32 w-auto drop-shadow-lg hover:scale-105 transition-transform duration-300">
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-3">
                Faça sua Doação
            </h1>
            <p class="text-lg text-gray-600 max-w-lg mx-auto">
                Cada contribuição ajuda a solidificar vidas e construir um futuro melhor para todos
            </p>
            <div class="mt-4">
                <a href="https://www.instagram.com/solidyficandovidas/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center space-x-2 text-emerald-600 hover:text-emerald-700 font-semibold transition-colors duration-200 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span>Conheça nosso projeto</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card do Formulário -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100 relative overflow-hidden">
            <!-- Decoração de fundo -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-100 to-amber-100 rounded-full blur-3xl opacity-30 -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-emerald-100 to-amber-100 rounded-full blur-3xl opacity-30 -ml-32 -mb-32"></div>

            <div class="relative z-10">
                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg animate-slide-in">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg animate-slide-in">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($pixQrCode)
                    <!-- QR Code do Pix -->
                    <div class="mb-8 text-center animate-fade-in">
                        <div class="bg-gradient-to-br from-emerald-50 to-amber-50 rounded-2xl p-8 mb-4">
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">
                                QR Code gerado com sucesso! 🎉
                            </h3>
                            <p class="text-gray-600 mb-6">
                                Escaneie o código abaixo com o app do seu banco para finalizar a doação
                            </p>

                            <div class="bg-white p-6 rounded-xl inline-block shadow-lg">
                                <img src="data:image/png;base64,{{ $pixQrCode }}" alt="QR Code Pix" class="w-64 h-64 mx-auto">
                            </div>

                            @if($pixPayload)
                                <div class="mt-6">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Ou copie o código Pix:</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <input
                                            type="text"
                                            value="{{ $pixPayload }}"
                                            readonly
                                            id="pixPayload"
                                            class="flex-1 max-w-md px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm font-mono text-gray-700"
                                        >
                                        <button
                                            type="button"
                                            onclick="navigator.clipboard.writeText(document.getElementById('pixPayload').value); this.innerHTML = '✓ Copiado!'; setTimeout(() => this.innerHTML = 'Copiar', 2000)"
                                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-medium"
                                        >
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6">
                                <button
                                    type="button"
                                    wire:click="$set('pixQrCode', null)"
                                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium"
                                >
                                    Fazer nova doação
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <form wire:submit="submit" class="space-y-6">
                    <!-- Tipo de Documento -->
                    <div x-data="{ documentType: @entangle('documentType') }">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Tipo de Documento
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <button
                                type="button"
                                @click="documentType = 'cpf'"
                                :class="documentType === 'cpf'
                                    ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg scale-105'
                                    : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-emerald-300'"
                                class="relative p-4 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>CPF</span>
                                </div>
                            </button>

                            <button
                                type="button"
                                @click="documentType = 'cnpj'"
                                :class="documentType === 'cnpj'
                                    ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg scale-105'
                                    : 'bg-white text-gray-700 border-2 border-gray-200 hover:border-amber-300'"
                                class="relative p-4 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                            >
                                <div class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>CNPJ</span>
                                </div>
                            </button>
                        </div>
                        @error('documentType')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- CPF / CNPJ -->
                    <div x-data="{
                        documentType: @entangle('documentType'),
                        displayDocument: '',
                        formatCPF(value) {
                            let num = value.replace(/\D/g, '');
                            if (num.length > 11) num = num.substring(0, 11);

                            if (num.length <= 3) return num;
                            if (num.length <= 6) return num.replace(/(\d{3})(\d+)/, '$1.$2');
                            if (num.length <= 9) return num.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
                            return num.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
                        },
                        formatCNPJ(value) {
                            let num = value.replace(/\D/g, '');
                            if (num.length > 14) num = num.substring(0, 14);

                            if (num.length <= 2) return num;
                            if (num.length <= 5) return num.replace(/(\d{2})(\d+)/, '$1.$2');
                            if (num.length <= 8) return num.replace(/(\d{2})(\d{3})(\d+)/, '$1.$2.$3');
                            if (num.length <= 12) return num.replace(/(\d{2})(\d{3})(\d{3})(\d+)/, '$1.$2.$3/$4');
                            return num.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d+)/, '$1.$2.$3/$4-$5');
                        },
                        handleDocumentInput(event) {
                            const value = event.target.value;
                            if (this.documentType === 'cpf') {
                                this.displayDocument = this.formatCPF(value);
                            } else {
                                this.displayDocument = this.formatCNPJ(value);
                            }
                            $wire.document = value.replace(/\D/g, '');
                        }
                    }">
                        <label for="document" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span x-text="documentType === 'cpf' ? 'CPF' : 'CNPJ'"></span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="document"
                                x-model="displayDocument"
                                @input="handleDocumentInput($event)"
                                :placeholder="documentType === 'cpf' ? '000.000.000-00' : '00.000.000/0000-00'"
                                :maxlength="documentType === 'cpf' ? 14 : 18"
                                class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('document') border-red-300 @enderror"
                            >
                        </div>
                        @error('document')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nome / Razão Social -->
                    <div x-data="{ documentType: @entangle('documentType') }">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span x-text="documentType === 'cpf' ? 'Nome Completo' : 'Nome Fantasia'"></span>
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                :placeholder="documentType === 'cpf' ? 'Digite seu nome completo' : 'Digite o nome fantasia'"
                                class="block w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('name') border-red-300 @enderror"
                            >
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Valor da Doação -->
                    <div x-data="{
                        focused: false,
                        displayValue: '',
                        formatMoney(value) {
                            let num = value.replace(/\D/g, '');
                            if (num === '') return '';
                            num = parseInt(num) / 100;
                            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        },
                        handleInput(event) {
                            const formatted = this.formatMoney(event.target.value);
                            this.displayValue = formatted;
                            const numericValue = formatted.replace(/\./g, '').replace(',', '.');
                            $wire.amount = numericValue || '';
                        }
                    }">
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            Valor da Doação
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-lg font-semibold">R$</span>
                            </div>
                            <input
                                type="text"
                                id="amount"
                                x-model="displayValue"
                                @input="handleInput($event)"
                                placeholder="0,00"
                                @focus="focused = true"
                                @blur="focused = false"
                                class="block w-full pl-14 pr-4 py-3.5 border-2 border-gray-200 rounded-xl text-lg font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('amount') border-red-300 @enderror"
                            >
                            <div
                                x-show="focused && !displayValue"
                                x-transition
                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"
                            >
                                <span class="text-gray-400 text-sm">Qualquer valor ajuda!</span>
                            </div>
                        </div>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Botão de Submit -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-600 hover:from-emerald-600 hover:via-emerald-700 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center justify-center space-x-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <svg wire:loading.remove class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <svg wire:loading class="animate-spin w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove class="text-lg">Confirmar Doação</span>
                            <span wire:loading class="text-lg">Gerando QR Code...</span>
                        </button>
                    </div>
                </form>
                @endif

                <!-- Footer Info -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Transação 100% segura e protegida</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats ou informações adicionais -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">+1.500</p>
                        <p class="text-sm text-gray-600">Acolhidos</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">100%</p>
                        <p class="text-sm text-gray-600">Transparência</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">+500</p>
                        <p class="text-sm text-gray-600">Doadores</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }
    </style>
</div>
