<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\qrCode as QrCodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeController extends Controller
{
    private const QR_TARGET_BASE_URL = 'https://laporan-sac.sac-po.com/send-img/laporan';

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $qrCodes = QrCodeModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('data', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.admin.qrcode.index', compact('qrCodes', 'search'));
    }

    public function create()
    {
        return view('pages.admin.qrcode.create');
    }

    public function edit($id)
    {
        $qrCode = QrCodeModel::findOrFail($id);

        return view('pages.admin.qrcode.create', compact('qrCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => ['required', 'string', 'max:255'],
        ]);

        $targetUrl = self::QR_TARGET_BASE_URL . '?n=' . rawurlencode($request->data);
        $filename = 'qr/' . Str::uuid() . '.png';
        $qrImage = QrCodeGenerator::format('png')
            ->size(300)
            ->margin(1)
            ->generate($targetUrl);

        Storage::disk('public')->put($filename, $qrImage);

        QrCodeModel::create([
            'qr' => $filename,
            'data' => $request->data,
        ]);

        return redirect()
            ->route('admin-qrcode.index')
            ->with('success', 'QR Code berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'data' => ['required', 'string', 'max:255'],
        ]);

        $qrCode = QrCodeModel::findOrFail($id);
        $targetUrl = self::QR_TARGET_BASE_URL . '?n=' . rawurlencode($request->data);
        $qrImage = QrCodeGenerator::format('png')
            ->size(300)
            ->margin(1)
            ->generate($targetUrl);

        Storage::disk('public')->put($qrCode->qr, $qrImage);

        $qrCode->update([
            'data' => $request->data,
        ]);

        return redirect()
            ->route('admin-qrcode.index')
            ->with('success', 'QR Code berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $qrCode = QrCodeModel::findOrFail($id);
        FileHelper::deleteImage($qrCode->qr);
        $qrCode->delete();

        return redirect()
            ->route('admin-qrcode.index')
            ->with('success', 'QR Code berhasil dihapus.');
    }
}
