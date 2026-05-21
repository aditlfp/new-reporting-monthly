export function blobToFile(blob, fileName) {
  return new File([blob], fileName, {
    type: 'image/jpeg',
    lastModified: Date.now(),
  });
}

export function isImageFromCamera(file) {
  return new Promise((resolve) => {
    const reader = new FileReader();

    reader.onload = (e) => {
      const img = new Image();
      img.onload = () => {
        EXIF.getData(img, function onExifRead() {
          const dateTimeOriginal = EXIF.getTag(this, 'DateTimeOriginal');
          const make = EXIF.getTag(this, 'Make');
          const model = EXIF.getTag(this, 'Model');
          const software = EXIF.getTag(this, 'Software');
          resolve(Boolean(dateTimeOriginal || make || model || software));
        });
      };
      img.src = e.target.result;
    };

    reader.readAsDataURL(file);
  });
}

function drawTimestampOverlay(canvas, ctx, locationString, userName, userJob) {
  const now = new Date();
  const timeString = now.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
  });

  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  const dateString = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

  const baseFont = Math.max(canvas.width / 42, 13);
  const timeFont = baseFont * 1.15;
  const dateFont = baseFont * 0.85;
  const locationFont = baseFont * 0.75;
  const nameFont = baseFont * 0.95;
  const jobFont = baseFont * 0.7;
  const padding = baseFont * 0.55;
  const gap = baseFont * 0.35;

  const leftX = padding;
  const rightX = canvas.width - padding;
  const locationY = canvas.height - padding;
  const dateY = locationY - locationFont - gap;
  const timeY = dateY - dateFont - gap;
  const nameY = canvas.height - padding;
  const jobY = nameY - nameFont - gap;

  const bgTop = timeY - timeFont - padding;
  const bgHeight = (Math.max(locationY, nameY) - bgTop) + padding;

  ctx.fillStyle = 'rgba(0, 0, 0, 0.38)';
  ctx.fillRect(0, bgTop, canvas.width, bgHeight);

  ctx.fillStyle = '#FFFFFF';
  ctx.textAlign = 'left';
  ctx.font = `700 ${timeFont}px 'Helvetica Neue', Arial, sans-serif`;
  ctx.fillText(timeString, leftX, timeY);

  ctx.font = `${dateFont}px 'Helvetica Neue', Arial, sans-serif`;
  ctx.fillText(dateString, leftX, dateY);

  ctx.font = `${locationFont}px 'Helvetica Neue', Arial, sans-serif`;
  ctx.globalAlpha = 0.9;
  ctx.fillText(locationString, leftX, locationY);
  ctx.globalAlpha = 1;

  ctx.textAlign = 'right';
  ctx.font = `600 ${nameFont}px 'Helvetica Neue', Arial, sans-serif`;
  ctx.fillText(userName, rightX, nameY);

  ctx.font = `${jobFont}px 'Helvetica Neue', Arial, sans-serif`;
  ctx.globalAlpha = 0.85;
  ctx.fillText(userJob, rightX, jobY);
  ctx.globalAlpha = 1;
}

export function addTimestampToImage(imageSrc, userName, userJob) {
  return new Promise((resolve) => {
    const img = new Image();
    img.crossOrigin = 'anonymous';

    img.onload = () => {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

      const finalize = (locationString) => {
        drawTimestampOverlay(canvas, ctx, locationString, userName, userJob);
        canvas.toBlob((blob) => resolve(blob), 'image/jpeg', 0.92);
      };

      if (!navigator.geolocation) {
        finalize('Lokasi tidak tersedia');
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${pos.coords.latitude}&lon=${pos.coords.longitude}`)
            .then((r) => r.json())
            .then((d) => {
              const city = d?.address?.city || d?.address?.town || d?.address?.village || '';
              const state = d?.address?.state || '';
              const locationString = city ? (state ? `${city}, ${state}` : city) : (state || 'Lokasi tidak diketahui');
              finalize(locationString);
            })
            .catch(() => finalize('Lokasi tidak tersedia'));
        },
        () => finalize('Lokasi tidak tersedia')
      );
    };

    img.src = imageSrc;
  });
}

export async function normalizeImageFile(file, userName, userJob) {
  const fromCamera = await isImageFromCamera(file);

  if (!fromCamera) {
    return file;
  }

  const dataUrl = await new Promise((resolve) => {
    const reader = new FileReader();
    reader.onload = (event) => resolve(event.target.result);
    reader.readAsDataURL(file);
  });

  const blob = await addTimestampToImage(dataUrl, userName, userJob);
  return blobToFile(blob, file.name);
}
