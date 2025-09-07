<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showDetailBooking()
    {
        // Nanti bisa ditambahkan logic untuk mengambil data booking
        return view('payment.detail-booking');
    }

    public function showPayment()
    {
        // Nanti bisa ditambahkan logic untuk mengambil data pembayaran
        return view('payment.payment');
    }
}
