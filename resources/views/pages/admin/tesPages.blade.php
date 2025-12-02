<div style="display: flex; justify-content: center; justify-items: start; width: 100svw; height: 200%; padding-top: 14mm; background-color: black;">
    @php
        use Carbon\Carbon;
        $latterData = (object)[
            'latter_numbers' => '001/CS-MDR/X/2024',
            'latter_matters' => 'Laporan Pekerjaan Cleaning Service Periode Oktober 2024',
            'period' => 'Oktober 2024',
        ];
        $listItemsData = [
            'Pembersihan area ruang rawat inap dan ruang perawatan intensif.',
            'Sterilisasi alat medis sesuai protokol kesehatan.',
            'Pengelolaan limbah medis dan non-medis.',
        ];
        $listItems = '';
        foreach ($listItemsData as $item) {
            $listItems .= '<li style="margin-bottom: 4px; counter-increment: item;">' . $item . '</li>';
        }
        $date = Carbon::now();
        $formatted = $date->translatedFormat('d F Y');
    @endphp
<section
    style="width: 210mm; height: 297mm; position: relative; margin: 0; padding: 0; overflow: hidden; page-break-after: always; background-color: white; font-family: Times New Roman, serif !important; font-size: 12pt; color: black;">
    <img src="{{ asset('img/header.png') }}" alt="header" srcset=""
        style="width: 84%; margin: 0 8.5%; font-size: 12pt;">
    <div style="margin-left: 32mm; line-height: 1; margin-top: 3mm;">
        <table style="table-layout: fixed; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="padding-right: 2mm;">Nomor</td>
                    <td>: {{ $latterData->latter_numbers }}</td>
                </tr>
                <tr>
                    <td>Lamp</td>
                    <td>: Satu bendel</td>
                </tr>
                <tr>
                    <td>Hal</td>
                    <td>: {{ $latterData->latter_matters }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <p style="margin-left: 48mm; margin-top: 3mm; line-height: 1.5;">
            Kepada Yth.<br>
            <strong>Direktur Rumah Sakit Paru Mangunharjo Kota Madiun</strong><br>
            Jl. Yos Sudarso No. 108-112, Kota Madiun
        </p>
    </div>
    <div>
        <p style="margin-left: 48mm; margin-top: 3mm; line-height: 1.5;">
            <strong style="font-style: italic;">
                Assalamu'alaikumWarahmatullahiWabarakatuh
            </strong>
        </p>
    </div>
    <div style="margin-left: 48mm; margin-right: 22mm; margin-top: 3mm; line-height: 1.5;">
        <p style="line-height: 1.25;">Puji syukur kita panjatkan kehadirat Allah SWT yang telah melimpahkan taufiq,
            hidayah serta kesehatan kepada kita semua. Amin</p>
        <p style="line-height: 1.25;">Bersama dengan ini kami sampaikan Laporan Pekerjaan Cleaning Service di Rumah
            Sakit Paru Mangunharjo Kota Madiun untuk periode {{ $latterData->period }}. </p>
        <p>Adapun isi laporan pekerjaan kami adalah sebagai berikut: </p>
        <ol style="line-height: 1.25; margin: 0; padding-left: 20px; list-style-type: none; counter-reset: item;">
            {{ $listItems }}
        </ol>
        <p style="line-height: 1.25;">Besar harapan kami untuk selalu dapat bersama mendukung kemajuan Rumah Sakit
            Paru Mangunharjo Kota Madiun serta memberikan <strong>“Pelayanan Dengan Lebih
                Baik”</strong> dalam pekerjaan Kritik dan saran sangatlah di harapkan demi terciptanya
            peningkatan kinerja kami. </p>
        <p style="line-height: 1.25;">Atas perhatian dan kerjasama Bapak/Ibu kami sampaikan terima kasih. </p>
        <p><strong style="font-style: italic;">Wassalamu'alaikumWarahmatullahiWabarakatuh</strong></p>
    </div>
    <div>
        <p style="margin-left: 134mm; margin-right: 22mm; margin-top: 10mm; line-height: 1.25;">
            Ponorogo, {{$formatted}}<br>
            Manager Cleaning Service<br><br><br><br><br><br>
            <strong style="text-align: center; display: flex; justify-content: center;">Suparno</strong>
        </p>
    </div>
</section>
</div>
