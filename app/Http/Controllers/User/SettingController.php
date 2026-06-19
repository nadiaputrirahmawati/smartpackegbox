<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function showPinSecurity(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Users/SettingPin', [
            // Kirim PIN asli (atau null) ke Vue
            'currentPin' => $user->pin 
        ]);
    }

    // Memproses pembaruan PIN
    public function updatePin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newPin' => 'required|array|size:5',
            'newPin.*' => 'required|numeric|min:0|max:9',
            'confirmPin' => 'required|array|size:5',
            'confirmPin.*' => 'required|numeric|min:0|max:9',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $newPinStr = implode('', $request->newPin);
        $confirmPinStr = implode('', $request->confirmPin);

        if ($newPinStr !== $confirmPinStr) {
            return redirect()->back()->with('error', 'Konfirmasi PIN tidak cocok.');
        }

        // Simpan langsung sebagai plain text string
        $user = $request->user();
        $user->pin = $newPinStr;
        $user->save();

        return redirect()->back()->with('success', 'PIN akses berhasil diperbarui.');
    }

    // Memproses reset PIN (Misal direset jadi kosong/null)
    public function resetPin(Request $request)
    {
        $user = $request->user();
        $user->pin = null;
        $user->save();

        return redirect()->back()->with('success', 'PIN akses telah direset. Silakan buat PIN baru.');
    }
}
