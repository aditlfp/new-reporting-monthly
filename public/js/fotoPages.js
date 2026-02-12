function getFotoPageHtml(data, currentMonth) {
  // Format month name in Indonesian
  const monthNames = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];
  const [year, month] = currentMonth.split("-");
  const monthName = monthNames[parseInt(month) - 1];
  console.log(monthName);
  const periodString = `PERIODE BULAN ${monthName.toUpperCase()} ${year}`;

  // Split data into chunks with different sizes for first page and subsequent pages
  const chunks = [];

  // First page gets 2 records
  if (data.length > 0) {
    chunks.push(data.slice(0, 2));
  }

  // Subsequent pages get 3 records each
  for (let i = 2; i < data.length; i += 3) {
    chunks.push(data.slice(i, i + 3));
  }

  const pages = [];

  chunks.forEach((chunk, pageIndex) => {
    let html = `
            <section style="height: 210mm; width: 297mm; position: relative; margin: 0; padding: 0; overflow: hidden; 
                   background-color: white; font-family: Arial, Helvetica, sans-serif !important; 
                   font-size: 12pt; color: black;">
        `;

    // Only add header to the first page
    if (pageIndex === 0) {
      html += `
                <div style="margin-top: 60pt; font-stretch: condensed;">
                    <p style="text-align: center; font-weight: bold; font-size: 20pt; text-transform: uppercase;">
                        FOTO KEGIATAN KEBERSIHAN CLEANING SERVICE <br>
                        PT SURYA AMANAH CENDIKIA PONOROGO <br>
                        AREA ${data[0].clients.name} <br>
                        ${periodString}
                    </p>
                </div>
            `;
    }

    // Adjust margin-top for subsequent pages to compensate for missing header
    const marginTop = pageIndex === 0 ? "20pt" : "20pt";

    html += `
                <div style="margin-top: ${marginTop}; margin-left: 20pt; margin-right: 20pt; font-stretch: condensed;">
                    <table style="width: 100%; border-collapse: collapse; text-align: center; table-layout: fixed;">
                        <colgroup>
                            <col style="width:4%;">
                            <col style="width:24%;">
                            <col style="width:24%;">
                            <col style="width:24%;">
                            <col style="width:24%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th style="padding:16px 0; border: 1px solid black;">NO</th>
                                <th colspan="3" style="padding:16px 0; border: 1px solid black;">FOTO PROGRES PENGERJAAN</th>
                                <th style="padding:16px 0; border: 1px solid black;">URAIAN PEKERJAAN</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

    // Calculate the starting index for numbering
    const startIndex = pageIndex === 0 ? 0 : 2 + (pageIndex - 1) * 3;

    // Add rows for this chunk
    chunk.forEach((item, index) => {
      const globalIndex = startIndex + index + 1;
      const beforeImage = item.img_before
        ? `${window.location.origin}/storage/${item.img_before}`
        : "https://placehold.co/200";
      const progressImage = item.img_proccess
        ? `${window.location.origin}/storage/${item.img_proccess}`
        : "https://placehold.co/200";
      const afterImage = item.img_final
        ? `${window.location.origin}/storage/${item.img_final}`
        : "https://placehold.co/200";
      const note = item.note || "-";

      html += `
                <tr>
                    <td style="border: 1px solid black;">${globalIndex}.</td>
                    <td class="square-cell"><img src="${beforeImage}" alt="before"></td>
                    <td class="square-cell"><img src="${progressImage}" alt="proses"></td>
                    <td class="square-cell"><img src="${afterImage}" alt="after"></td>
                    <td style="border: 1px solid black; text-align: left; padding: 0 10pt;">${note}</td>
                </tr>
            `;
    });

    html += `
                        </tbody>
                    </table>
                </div>
            </section>
        `;

    pages.push(html);
  });

  return pages;
}
