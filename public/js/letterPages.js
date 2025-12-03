function getLetterPageHtml(latterData, assetUrl) {
  const date = new Date(latterData.created_at);
  const formatted = date.toLocaleDateString("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
  });

  // Split the report_content by newlines to get individual items
  const reportItems = latterData.report_content
    ? latterData.report_content.split("\n")
    : [];

  function toCapitalize(str) {
    return str.toLowerCase().replace(/\b\w/g, (char) => char.toUpperCase());
  }

  // Generate list items with proper formatting
  const listItems = reportItems
    .map((item, index) => {
      // Remove existing numbering pattern (e.g., "1. ", "2. ", etc.)
      const cleanedItem = item.replace(/^\d+\.\s*/, "");

      return `<li style="margin-bottom: 5px; padding-left: 0; text-indent: 0; display: flex;">
                <span style="min-width: 1.5em; text-align: right; padding-right: 0.5em;">${
                  index + 1
                }.</span>
              <span>${cleanedItem}</span>
            </li>`;
    })
    .join("");

  return `
        <section
        style="width: 210mm; height: 297mm; position: relative; margin: 0; padding: 0; overflow: hidden; page-break-after: always; background-color: white; font-family: Times New Roman, serif !important; font-size: 12pt; color: black;">
        <img src="${
          window.location.origin + "/img/header.png"
        }" alt="header" srcset=""
            style="width: 84%; margin: 0 8.5%; font-size: 12pt;">
        <div style="margin-left: 30mm; line-height: 1; margin-top: 3mm;">
            <table style="table-layout: fixed; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="padding-right: 2mm;">Nomor</td>
                        <td>: ${latterData.latter_numbers}</td>
                    </tr>
                    <tr>
                        <td>Lamp</td>
                        <td>: ${latterData.lamp}</td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>: ${latterData.latter_matters}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <p style="margin-left: 46mm; margin-top: 3mm; line-height: 1.5; ">
                Kepada Yth.<br>
                <strong>${latterData.letter_to}</strong><br>
                ${
                  toCapitalize(latterData.cover.client.address) +
                  " " +
                  toCapitalize(latterData.cover.client.kabupaten)
                }
            </p>
        </div>
        <div>
            <p style="margin-left: 46mm; margin-top: 3mm; line-height: 1.5;">
                <strong style="font-style: italic;">
                    Assalamu'alaikumWarahmatullahiWabarakatuh
                </strong>
            </p>
        </div>
        <div style="margin-left: 46mm; margin-right: 22mm; margin-top: 3mm; line-height: 1.5;">
            <p style="line-height: 1.25;">Puji syukur kita panjatkan kehadirat Allah SWT yang telah melimpahkan taufiq,
                hidayah serta kesehatan kepada kita semua. Amin</p>
            <p style="line-height: 1.25; margin-top: 3mm;">Bersama dengan ini kami sampaikan Laporan Pekerjaan Cleaning Service di ${toCapitalize(
              latterData.cover.client.name
            )} untuk periode ${latterData.period}. </p>
            <p style="margin: 4mm 0;">Adapun isi laporan pekerjaan kami adalah sebagai berikut: </p>
            <ol style="line-height: 1.25; margin: 0; padding-left: 4px; list-style-type: none; counter-reset: item;">
                ${listItems}
            </ol>
            <p style="line-height: 1.25;">Besar harapan kami untuk selalu dapat bersama mendukung kemajuan ${toCapitalize(
              latterData.cover.client.name
            )} serta memberikan <strong>“Pelayanan Dengan Lebih
                    Baik”</strong> dalam pekerjaan Kritik dan saran sangatlah di harapkan demi terciptanya
                peningkatan kinerja kami. </p>
            <p style="line-height: 1.25; margin: 4mm 0;">Atas perhatian dan kerjasama Bapak/Ibu kami sampaikan terima kasih. </p>
            <p><strong style="font-style: italic;">Wassalamu'alaikumWarahmatullahiWabarakatuh</strong></p>
        </div>
        <div style="position: relative; bottom: 0mm;">
            <p style="margin-left: 134mm; margin-right: 22mm; margin-top: 10mm; line-height: 1.25;">
                Ponorogo, ${formatted}<br>
                Manager Cleaning Service<br><br><br><br><br><br>
                <strong style="text-align: center; display: flex; justify-content: center; text-decoration-line: underline;">Suparno</strong>
            </p>
            <img src="${
              window.location.origin + "/img/stampel.png"
            }" alt="footer" srcset=""
                style="width: 3.2cm; height: 3.2cm; font-size: 12pt; position: absolute; bottom: 4mm; rotate: 3deg; right: 50mm; opacity: 0.85;">
            <img src="${
              window.location.origin + "/img/ttdParno.png"
            }" alt="footer" srcset=""
                style="width: 3.4cm; font-size: 12pt; position: absolute; z-index: 2; bottom: 8mm; right: 36mm;">
        </div>
    </section>
    `;
}
