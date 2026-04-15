export function combineNoteWithArea(note, area) {
  if (area && area.trim()) {
    return `${note} - Area ${area}`;
  }
  return note;
}

export function extractAreaFromNote(combinedNote) {
  if (!combinedNote) return { note: '', area: '' };

  const marker = ' - Area ';
  const lastIndex = combinedNote.lastIndexOf(marker);

  if (lastIndex !== -1) {
    return {
      note: combinedNote.substring(0, lastIndex),
      area: combinedNote.substring(lastIndex + marker.length),
    };
  }

  return { note: combinedNote, area: '' };
}

export function debounce(fn, wait) {
  let timeout;
  return function debounced(...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn.apply(this, args), wait);
  };
}

export function loadImageWithLazyLoading(imgElement, src) {
  if ('loading' in HTMLImageElement.prototype) {
    imgElement.loading = 'lazy';
    imgElement.src = src;
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        imgElement.src = src;
        observer.unobserve(imgElement);
      }
    });
  });

  observer.observe(imgElement);
}

export async function submitForm(formData, url, method, csrfToken) {
  return $.ajax({
    url,
    type: method,
    data: formData,
    processData: false,
    contentType: false,
    headers: {
      'X-CSRF-TOKEN': csrfToken,
    },
  });
}

export function notify(message, type = 'info') {
  if (typeof window.Notify === 'function') {
    window.Notify(message, null, null, type);
    return;
  }

  // Safety fallback if Notify is unavailable.
  console[type === 'error' ? 'error' : 'log'](message);
}

export function getCsrfToken() {
  return $('meta[name="csrf-token"]').attr('content');
}

export function createImageCard(imgData) {
  const createdDate = new Date(imgData.created_at);
  const monthYear = createdDate.toLocaleDateString('id-ID', {
    month: 'long',
    year: 'numeric',
  });
  const dayMonthYear = createdDate.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });

  const baseUrl = `${window.location.origin}/storage/`;
  const imgBefore = imgData.img_before ? baseUrl + imgData.img_before : 'https://placehold.co/400x400?text=Kosong';
  const imgProcess = imgData.img_proccess ? baseUrl + imgData.img_proccess : 'https://placehold.co/400x400?text=Kosong';
  const imgFinal = imgData.img_final ? baseUrl + imgData.img_final : 'https://placehold.co/400x400?text=Kosong';

  return `
    <div class="overflow-hidden transition-shadow bg-white border rounded-lg shadow-sm border-slate-100 hover:shadow-md">
      <div class="p-4">
        <div class="flex items-start justify-between mb-3">
          <div>
            <h4 class="font-semibold text-md text-slate-900">${monthYear}</h4>
            <p class="text-sm text-slate-500">${dayMonthYear}</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-3">
          <div class="overflow-hidden rounded-lg aspect-square bg-slate-100"><img data-src="${imgBefore}" alt="Before" class="object-cover w-full h-full lazy-load"></div>
          <div class="overflow-hidden rounded-lg aspect-square bg-slate-100"><img data-src="${imgProcess}" alt="Process" class="object-cover w-full h-full lazy-load"></div>
          <div class="overflow-hidden rounded-lg aspect-square bg-slate-100"><img data-src="${imgFinal}" alt="Final" class="object-cover w-full h-full lazy-load"></div>
        </div>
        <div class="mb-3"><p class="text-sm text-slate-700">${imgData.note}</p></div>
      </div>
    </div>
  `;
}
