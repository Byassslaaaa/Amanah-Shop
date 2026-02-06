<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use App\Models\System\Setting;
use App\Helpers\WhatsappHelper;

class ContactController extends Controller
{
    public function index()
    {
        // Get WhatsApp number from settings
        $whatsappNumber = Setting::get('admin_whatsapp');

        // Format WhatsApp URL
        $whatsappUrl = null;
        if ($whatsappNumber) {
            $formattedNumber = WhatsappHelper::formatPhoneNumber($whatsappNumber);
            $message = "Halo Amanah Shop, saya ingin bertanya mengenai produk/pesanan saya.";
            $whatsappUrl = "https://wa.me/{$formattedNumber}?text=" . urlencode($message);
        }

        return view('user.contact.index', [
            'whatsappUrl' => $whatsappUrl,
            'whatsappNumber' => $whatsappNumber
        ]);
    }
}
