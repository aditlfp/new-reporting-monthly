importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
    console.log("Workbox loaded ✔");
} else {
    console.log("Workbox failed to load ❌");
}

// ---------------------------------------------
// Background Sync Queue
// ---------------------------------------------
const uploadQueue = new workbox.backgroundSync.Queue('uploadQueue', {
    maxRetentionTime: 24 * 60 // retry for 24 hours
});

self.addEventListener("sync", function(event) {
    if (event.tag === "sync-reports") {
        event.waitUntil(syncDraftReports());
    }
});


// ---------------------------------------------
// Intercept POST upload request (example: /upload-img-lap)
// ---------------------------------------------
workbox.routing.registerRoute(
    ({url, request}) =>
        request.method === "POST" &&
        url.pathname.startsWith('/upload-img-lap'),

    async ({event}) => {
        try {
            // Try sending to server
            return await fetch(event.request.clone());
        } catch (err) {
            // Save to queue if offline
            console.log("Offline → added to uploadQueue");
            await uploadQueue.pushRequest({request: event.request.clone()});

            // Return fake response to front-end
            return new Response(
                JSON.stringify({ queued: true }),
                { status: 202, headers: { 'Content-Type': 'application/json' } }
            );
        }
    },
    'POST'
);

async function syncDraftReports() {
    const db = await openIndexedDB();

    const drafts = await dbGetAll("drafts");

    for (let draft of drafts) {
        let formData = new FormData();
        formData.append("note", draft.note);

        if (draft.img_before) formData.append("img_before", draft.img_before);
        if (draft.img_proccess) formData.append("img_proccess", draft.img_proccess);
        if (draft.img_final) formData.append("img_final", draft.img_final);

        try {
            console.log(formData)
            const response = await fetch('/upload-img-lap', {
                method: "POST",
                body: formData
            });

            if (response.ok) {
                await dbDelete("drafts", draft.id);
            }
        } catch (e) {
            console.log("Sync error:", e);
        }
    }
}


// ---------------------------------------------
// Static asset caching
// ---------------------------------------------
workbox.precaching.precacheAndRoute(self.__WB_MANIFEST || []);

// ---------------------------------------------
self.addEventListener("install", event => {
    self.skipWaiting();
});

self.addEventListener("activate", event => {
    clients.claim();
});
