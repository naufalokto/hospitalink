<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - HOSPITALINK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .font-satoshi {
            font-family: 'Inter', sans-serif;
        }

        .invoice-header {
            background: #183A5B;
        }

        .invoice-title {
            color: white;
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 24px;
        }

        .section-title {
            color: black;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 14px;
        }

        .patient-name {
            color: black;
            font-size: 13px;
            font-weight: 700;
            line-height: 13px;
        }

        .patient-detail {
            color: black;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .contact-label {
            color: black;
            font-size: 12px;
            font-weight: 700;
            line-height: 12px;
        }

        .contact-value {
            color: black;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .email-label {
            color: #0A2540;
            font-size: 12px;
            font-weight: 700;
            line-height: 12px;
        }

        .email-value {
            color: #0A2540;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .address-detail {
            color: #0A2540;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .bank-detail-label {
            color: black;
            font-size: 10px;
            font-weight: 500;
            line-height: 10px;
        }

        .bank-detail-value {
            color: black;
            font-size: 10px;
            font-weight: 400;
            line-height: 10px;
        }

        .invoice-info-label {
            color: black;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 12px;
        }

        .invoice-info-value {
            color: black;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .table-header {
            background: #183A5B;
            color: white;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 11px;
        }

        .table-cell {
            color: black;
            font-size: 11px;
            font-weight: 400;
            line-height: 11px;
        }

        .table-cell-bold {
            color: black;
            font-size: 11px;
            font-weight: 700;
            line-height: 11px;
        }

        .total-label {
            color: black;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 11px;
        }

        .total-value {
            color: black;
            font-size: 11px;
            font-weight: 700;
            line-height: 11px;
        }

        .signature-name {
            color: black;
            font-size: 14px;
            font-weight: 500;
            line-height: 14px;
        }

        .signature-title {
            color: black;
            font-size: 12px;
            font-weight: 400;
            line-height: 12px;
        }

        .terms-title {
            color: black;
            font-size: 13px;
            font-weight: 700;
            line-height: 13px;
        }

        .terms-text {
            color: black;
            font-size: 11px;
            font-weight: 400;
            line-height: 11px;
        }

        .thank-you {
            color: black;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 12px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- Header -->
        <div class="bg-white shadow-sm px-4 py-3 no-print">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}"
                    class="text-gray-600 hover:text-gray-800 transition-colors flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Invoice</h1>
                <div class="w-6"></div>
            </div>
        </div>

        <!-- Invoice List -->
        <div class="p-4 space-y-4">
            @forelse($transactionDetails as $transaction)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Invoice Header -->
                    <div class="invoice-header p-4">
                        <div class="flex justify-between items-center">
                            <div class="invoice-title font-satoshi">INVOICE</div>
                            <div class="text-right text-white">
                                <div class="text-sm font-bold">{{ $transaction->transaction_number }}</div>
                                <div class="text-xs">{{ $transaction->payment_completed_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Content -->
                    <div class="p-4 bg-gray-50">
                        <!-- Patient Information -->
                        <div class="mb-4">
                            <div class="section-title font-satoshi mb-2">INVOICE TO:</div>
                            <div class="patient-name font-satoshi mb-1">{{ $transaction->patient_name }}</div>
                            <div class="patient-detail font-satoshi">{{ $transaction->hospital_name }}</div>
                            @if ($transaction->patient_address)
                                <div class="patient-detail font-satoshi">{{ $transaction->patient_address }}</div>
                            @endif
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4">

                            @if ($transaction->patient_email)
                                <div class="flex items-center">
                                    <span class="email-label font-satoshi">E:</span>
                                    <span class="email-value font-satoshi ml-1">{{ $transaction->patient_email }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Hospital Information -->
                        <div class="mb-4">
                            <div class="section-title font-satoshi mb-2">ADDRESS</div>
                            <div class="patient-name font-satoshi mb-1">{{ $transaction->hospital_name }}</div>
                            @if ($transaction->hospital->address)
                                <div class="patient-detail font-satoshi">{{ $transaction->hospital->address }}</div>
                            @endif
                            <div class="address-detail font-satoshi">Indonesia</div>
                        </div>

                        <!-- Invoice Details -->
                        <div class="mb-4">
                            <div class="section-title font-satoshi mb-2">CONTACT</div>
                            <div class="mb-2">
                                <div class="patient-name font-satoshi mb-1">Phone</div>
                                <div class="contact-value font-satoshi">{{ $transaction->hospital->phone_number }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="patient-name font-satoshi mb-1">Email</div>
                                <div class="contact-value font-satoshi">
                                    {{ $transaction->hospital->email ?? 'ehospital.app@gmail.com' }}</div>
                            </div>
                        </div>

                        <!-- Bank Details -->
                        <div class="mb-4">
                            <div class="patient-name font-satoshi mb-2">Bank Details</div>
                            @if ($transaction->va_number)
                                <div class="flex items-center mb-1">
                                    <span class="bank-detail-label font-satoshi">Account No. :</span>
                                    <span
                                        class="bank-detail-value font-satoshi ml-1">{{ $transaction->va_number }}</span>
                                </div>
                            @endif
                            @if ($transaction->bank_code)
                                <div class="flex items-center">
                                    <span class="bank-detail-label font-satoshi">Code :</span>
                                    <span
                                        class="bank-detail-value font-satoshi ml-1 uppercase">{{ $transaction->bank_code }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Invoice Information -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="invoice-info-label font-satoshi">Invoice No.:</span>
                                <span
                                    class="invoice-info-value font-satoshi">{{ $transaction->transaction_number }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="invoice-info-label font-satoshi">Invoice Date :</span>
                                <span
                                    class="invoice-info-value font-satoshi">{{ $transaction->payment_completed_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="invoice-info-label font-satoshi">Due Date :</span>
                                <span
                                    class="invoice-info-value font-satoshi uppercase">{{ $transaction->check_in_date->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Invoice Table -->
                        <div class="mb-4">
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <!-- Table Header -->
                                <div class="table-header flex">
                                    <div class="w-32 p-2">Item Description</div>
                                    <div class="flex-1 p-2">Price</div>
                                    <div class="flex-1 p-2">QTY</div>
                                    <div class="flex-1 p-2">Total</div>
                                </div>

                                <!-- Table Rows -->
                                <div class="bg-white">
                                    <div class="flex">
                                        <div class="w-32 p-2 table-cell font-satoshi">
                                            {{ $transaction->room_type_name }}</div>
                                        <div class="flex-1 p-2 table-cell-bold font-satoshi">
                                            {{ $transaction->formatted_price_per_day }}</div>
                                        <div class="flex-1 p-2 table-cell font-satoshi">
                                            {{ $transaction->duration_days }}</div>
                                        <div class="flex-1 p-2 table-cell-bold font-satoshi">
                                            {{ $transaction->formatted_total_amount }}</div>
                                    </div>
                                </div>

                                <!-- Totals -->
                                <div class="bg-gray-100 border-t border-gray-300">
                                    <div class="flex">
                                        <div class="w-32 p-2 total-label font-satoshi">Grand Total</div>
                                        <div class="flex-1 p-2"></div>
                                        <div class="flex-1 p-2"></div>
                                        <div class="flex-1 p-2 text-right total-value font-satoshi">
                                            {{ $transaction->formatted_total_amount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Action Buttons -->
                        <div class="flex gap-2 no-print">
                            <a href="{{ route('booking.invoice', $transaction->booking_id) }}"
                                class="flex-1 bg-blue-500 text-white text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                📄 Lihat Detail
                            </a>
                            <button onclick="window.print()"
                                class="bg-green-500 text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                🖨️ Cetak
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📄</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada invoice</h3>
                    <p class="text-gray-600 mb-4">Anda belum memiliki invoice booking kamar rumah sakit</p>
                    <a href="{{ route('room') }}"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                        Lihat Kamar Tersedia
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($transactionDetails->hasPages())
                <div class="mt-6">
                    {{ $transactionDetails->links() }}
                </div>
            @endif
        </div>

        <!-- Thank You Message -->
        @if ($transactionDetails->count() > 0)
            <div class="text-center py-4">
                <div class="thank-you font-satoshi">Thank You For Your Business!</div>
            </div>
        @endif
    </div>

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <div class="max-w-6xl mx-auto p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8 no-print">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Invoice</h1>
                    <p class="text-gray-600 mt-1">Semua invoice booking kamar rumah sakit Anda</p>
                </div>
                <a href="{{ route('room') }}"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    + Booking Baru
                </a>
            </div>

            <!-- Invoice Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @forelse($transactionDetails as $transaction)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Invoice Header -->
                        <div class="invoice-header p-6">
                            <div class="flex justify-between items-center">
                                <div class="invoice-title font-satoshi">INVOICE</div>
                                <div class="text-right text-white">
                                    <div class="text-lg font-bold">{{ $transaction->transaction_number }}</div>
                                    <div class="text-sm">{{ $transaction->payment_completed_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Content -->
                        <div class="p-6 bg-gray-50">
                            <!-- Patient Information -->
                            <div class="mb-6">
                                <div class="section-title font-satoshi mb-3">INVOICE TO:</div>
                                <div class="patient-name font-satoshi mb-2">{{ $transaction->patient_name }}</div>
                                <div class="patient-detail font-satoshi">{{ $transaction->hospital_name }}</div>
                                @if ($transaction->patient_address)
                                    <div class="patient-detail font-satoshi">{{ $transaction->patient_address }}</div>
                                @endif
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-6">
                                <div class="flex items-center mb-2">
                                    <span class="contact-label font-satoshi">P:</span>
                                    <span
                                        class="contact-value font-satoshi ml-2">{{ $transaction->patient_phone }}</span>
                                </div>
                                @if ($transaction->patient_email)
                                    <div class="flex items-center">
                                        <span class="email-label font-satoshi">E:</span>
                                        <span
                                            class="email-value font-satoshi ml-2">{{ $transaction->patient_email }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Hospital Information -->
                            <div class="mb-6">
                                <div class="section-title font-satoshi mb-3">ADDRESS</div>
                                <div class="patient-name font-satoshi mb-2">{{ $transaction->hospital_name }}</div>
                                @if ($transaction->hospital->address)
                                    <div class="patient-detail font-satoshi">{{ $transaction->hospital->address }}
                                    </div>
                                @endif
                                <div class="address-detail font-satoshi">Indonesia</div>
                            </div>

                            <!-- Invoice Details -->
                            <div class="mb-6">
                                <div class="section-title font-satoshi mb-3">CONTACT</div>
                                <div class="mb-3">
                                    <div class="patient-name font-satoshi mb-1">Phone</div>
                                    <div class="contact-value font-satoshi">{{ $transaction->hospital->phone_number }}</div>
                                </div>
                                <div class="mb-3">
                                    <div class="patient-name font-satoshi mb-1">Email</div>
                                    <div class="contact-value font-satoshi">
                                        {{ $transaction->hospital->email ?? 'ehospital.app@gmail.com' }}</div>
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <div class="mb-6">
                                <div class="patient-name font-satoshi mb-3">Bank Details</div>
                                @if ($transaction->va_number)
                                    <div class="flex items-center mb-2">
                                        <span class="bank-detail-label font-satoshi">Account No. :</span>
                                        <span
                                            class="bank-detail-value font-satoshi ml-2">{{ $transaction->va_number }}</span>
                                    </div>
                                @endif
                                @if ($transaction->bank_code)
                                    <div class="flex items-center">
                                        <span class="bank-detail-label font-satoshi">Code :</span>
                                        <span
                                            class="bank-detail-value font-satoshi ml-2 uppercase">{{ $transaction->bank_code }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Invoice Information -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="invoice-info-label font-satoshi">Invoice No.:</span>
                                    <span
                                        class="invoice-info-value font-satoshi">{{ $transaction->transaction_number }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="invoice-info-label font-satoshi">Invoice Date :</span>
                                    <span
                                        class="invoice-info-value font-satoshi">{{ $transaction->payment_completed_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="invoice-info-label font-satoshi">Due Date :</span>
                                    <span
                                        class="invoice-info-value font-satoshi uppercase">{{ $transaction->check_in_date->format('d M Y') }}</span>
                                </div>
                            </div>

                            <!-- Invoice Table -->
                            <div class="mb-6">
                                <div class="overflow-hidden rounded-lg border border-gray-200">
                                    <!-- Table Header -->
                                    <div class="table-header flex">
                                        <div class="w-40 p-3">Item Description</div>
                                        <div class="flex-1 p-3">Price</div>
                                        <div class="flex-1 p-3">QTY</div>
                                        <div class="flex-1 p-3">Total</div>
                                    </div>

                                    <!-- Table Rows -->
                                    <div class="bg-white">
                                        <div class="flex">
                                            <div class="w-40 p-3 table-cell font-satoshi">
                                                {{ $transaction->room_type_name }}</div>
                                            <div class="flex-1 p-3 table-cell-bold font-satoshi">
                                                {{ $transaction->formatted_price_per_day }}</div>
                                            <div class="flex-1 p-3 table-cell font-satoshi">
                                                {{ $transaction->duration_days }}</div>
                                            <div class="flex-1 p-3 table-cell-bold font-satoshi">
                                                {{ $transaction->formatted_total_amount }}</div>
                                        </div>
                                    </div>

                                    <!-- Totals -->
                                    <div class="bg-gray-100 border-t border-gray-300">
                                        <div class="flex">
                                            <div class="w-40 p-3 total-label font-satoshi">Grand Total</div>
                                            <div class="flex-1 p-3"></div>
                                            <div class="flex-1 p-3"></div>
                                            <div class="flex-1 p-3 text-right total-value font-satoshi">
                                                {{ $transaction->formatted_total_amount }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 no-print">
                                <a href="{{ route('booking.invoice', $transaction->booking_id) }}"
                                    class="flex-1 bg-blue-500 text-white text-center py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                    📄 Lihat Detail
                                </a>
                                <button onclick="window.print()"
                                    class="bg-green-500 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                    🖨️ Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-16">
                        <div class="text-gray-400 text-8xl mb-6">📄</div>
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">Belum ada invoice</h3>
                        <p class="text-gray-600 mb-8">Anda belum memiliki invoice booking kamar rumah sakit</p>
                        <a href="{{ route('room') }}"
                            class="bg-blue-500 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                            Lihat Kamar Tersedia
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($transactionDetails->hasPages())
                <div class="mt-8">
                    {{ $transactionDetails->links() }}
                </div>
            @endif
        </div>

        <!-- Thank You Message -->
        @if ($transactionDetails->count() > 0)
            <div class="text-center py-6">
                <div class="thank-you font-satoshi text-lg">Thank You For Your Business!</div>
            </div>
        @endif
    </div>
</body>

</html>
