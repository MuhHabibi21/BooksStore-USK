<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Tampilkan Pesan Kontak Masuk
     */
    public function index()
    {
        $messages = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('messages'));
    }

    /**
     * Lihat Detail Pesan
     */
    public function show($id)
    {
        $message = Contact::findOrFail($id);
        $message->update(['is_read' => true]);

        return view('admin.contacts.show', compact('message'));
    }

    /**
     * Balas Pesan Pengguna
     */
    public function reply(Request $request, $id)
    {
        $message = Contact::findOrFail($id);

        $validated = $request->validate([
            'admin_reply' => 'required|string',
        ], [
            'admin_reply.required' => 'Isi pesan balasan wajib diisi.',
        ]);

        $message->update([
            'admin_reply' => $validated['admin_reply'],
            'replied_at' => now(),
            'is_read' => true,
        ]);

        return redirect()->route('admin.contacts.show', $message->id)
            ->with('success', "Balasan berhasil dikirim! Pengguna ({$message->name}) kini dapat melihat pesan balasan Anda.");
    }

    /**
     * Hapus Pesan
     */
    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
