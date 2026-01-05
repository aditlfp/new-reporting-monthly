importScripts(
  "https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"
);

self.onmessage = async function (e) {
  const { images } = e.data;

  try {
    const pdf = new jspdf.jsPDF({
      orientation: "landscape",
      unit: "mm",
      format: "a4",
    });

    for (let i = 0; i < images.length; i++) {
      const imgData = images[i];

      if (i > 0) pdf.addPage();

      const imgProps = pdf.getImageProperties(imgData);
      const pdfWidth = pdf.internal.pageSize.getWidth();
      const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

      pdf.addImage(imgData, "JPEG", 0, 0, pdfWidth, pdfHeight);

      const percent = Math.round(((i + 1) / images.length) * 100);
      self.postMessage({ progress: percent });
    }

    const pdfBlob = pdf.output("blob");
    self.postMessage({ done: true, pdfBlob });

  } catch (error) {
    self.postMessage({ error: error.message });
  }
};
