import { combineNoteWithArea } from './dom-helpers';

export function saveDraftOffline() {
  const content = $('#reportContent').val();
  const area = $('#reportArea').val();

  const draft = {
    id: Date.now(),
    note: combineNoteWithArea(content, area),
    img_before: $('#image1')[0]?.files?.[0] || null,
    img_proccess: $('#image2')[0]?.files?.[0] || null,
    img_final: $('#image3')[0]?.files?.[0] || null,
  };

  const request = indexedDB.open('reportDB', 1);

  request.onupgradeneeded = (event) => {
    const db = event.target.result;
    if (!db.objectStoreNames.contains('drafts')) {
      db.createObjectStore('drafts', { keyPath: 'id' });
    }
  };

  request.onsuccess = (event) => {
    const db = event.target.result;
    const tx = db.transaction('drafts', 'readwrite');
    tx.objectStore('drafts').put(draft);
  };
}

function detectBrowser() {
  const ua = navigator.userAgent;
  if (typeof InstallTrigger !== 'undefined') return 'Firefox';
  if (ua.includes('Edg/')) return 'Edge';
  if (window.chrome && !ua.includes('Edg/')) return 'Chrome';
  if (/^((?!chrome|android).)*safari/i.test(ua)) return 'Safari';
  return 'Unknown';
}

async function syncDrafts(config) {
  if (!window.idb || typeof window.loadLocalFile !== 'function') {
    return;
  }

  const db = await window.idb.openDB('reportDB', 1);
  const drafts = await db.getAll('drafts');

  for (const draft of drafts) {
    try {
      const form = new FormData();
      form.append('note', draft.note);
      form.append('img_before', await window.loadLocalFile(draft.img_before));
      form.append('img_proccess', await window.loadLocalFile(draft.img_proccess));
      form.append('img_final', await window.loadLocalFile(draft.img_final));

      await fetch(config.routes.store, {
        method: 'POST',
        body: form,
      });

      await db.delete('drafts', draft.id);
    } catch (error) {
      console.warn('Sync draft gagal, akan dicoba lagi', error);
      return;
    }
  }
}

export function initOfflineSync(config) {
  window.addEventListener('online', () => {
    const browser = detectBrowser();

    if ((browser === 'Chrome' || browser === 'Edge') && navigator.serviceWorker?.ready) {
      navigator.serviceWorker.ready.then((reg) => {
        reg.sync?.register('sync-reports').catch(() => syncDrafts(config));
      });
      return;
    }

    syncDrafts(config);
  });
}
