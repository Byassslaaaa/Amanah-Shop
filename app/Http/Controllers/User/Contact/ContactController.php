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
        $whatsappNumber = Setting::get('partner_whatsapp');

        // Format WhatsApp URL
        $whatsappUrl = null;
        if ($whatsappNumber) {
            $formattedNumber = WhatsappHelper::formatPhoneNumber($whatsappNumber);
            $message = "Halo Admin, saya tertarik untuk bergabung menjadi mitra. Mohon informasi lebih lanjut mengenai cara pendaftaran dan persyaratannya.";
            $whatsappUrl = "https://wa.me/{$formattedNumber}?text=" . urlencode($message);
        }

        return view('user.contact.index', [
            'whatsappUrl' => $whatsappUrl,
            'whatsappNumber' => $whatsappNumber
        ]);
    }
}
