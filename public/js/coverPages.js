// In a separate file or at the top of your script
function getCoverPageHtml(latterData, assetUrl) {
  return `
        <section style="width: 210mm; height: 297mm; position: relative; margin: 0; padding: 0; overflow: hidden; page-break-after: always;">
            <!-- Background image as absolute positioned element -->
            <img src="/img/COVER.svg"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1;"
                alt="Background">

            <div style="position: absolute; inset: 0; display: flex; flex-direction: column; padding: 0; margin: 0; margin-top: 50mm;">
                <div style="margin-top: 3mm; text-align: center;">
                    <div style="display: inline-block; padding: 1mm 3mm; font-size: 30pt; font-weight: bold; color: #323C8B; text-transform: uppercase; white-space: nowrap;">
                        ${latterData.cover.jenis_rekap}
                    </div>
                </div>

                <div style="margin-top: -6mm; text-align: center;">
                    <div style="display: inline-block; font-stretch: condensed; letter-spacing: 0.15em; padding: 1mm 3mm; font-size: 24pt; font-weight: bold; color: #323C8B; text-transform: uppercase; white-space: nowrap; max-width: 90%; overflow: hidden; text-overflow: ellipsis;">
                        (${
                          latterData.cover.client
                            ? latterData.cover.client.name
                            : "Unknown Client"
                        })
                    </div>
                </div>

                <div style="display: flex; justify-content: center; flex-grow: 1; gap: 10mm; margin: 24mm auto 0;">
                    <div style="width: 50%; padding-right: 1mm;">
                        <div style="display: flex; align-items: center; justify-center; width: 100%; height: 65mm;">
                            <img src="${assetUrl}/${latterData.cover.img_src_1.replace(
    /^\/+/,
    ""
  )}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Cover Image 1">
                        </div>
                    </div>

                    <div style="width: 50%; padding-left: 1mm;">
                        <div style="display: flex; align-items: center; justify-center; width: 100%; height: 65mm;">
                            <img src="${assetUrl}/${latterData.cover.img_src_2.replace(
    /^\/+/,
    ""
  )}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Cover Image 2">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 70mm; text-align: center;">
                    <div style="display: inline-block; font-stretch: condensed; letter-spacing: 0.15em; padding: 1mm 3mm; font-size: 24pt; color: oklch(17% 0 0); font-weight: bold; text-transform: uppercase; white-space: nowrap; max-width: 90%; overflow: hidden; text-overflow: ellipsis;">
                        PERIODE ${latterData.period}
                    </div>
                </div>
            </div>
        </section>
    `;
}
