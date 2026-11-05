<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

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
     * Hapus Pesan
     */
    public function destroy($id)
    {
        $message = Contact::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
