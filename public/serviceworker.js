const staticAssets = [
    './',
    './css/archeryosa.css',
    './plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    './plugins/jstree/style.css',
    './css/bootstrap.min.css',
    './css/icons.css',
    './css/scsslight/style.css',
    './js/modernizr.min.js',
    './js/jquery.min.js',
    './js/popper.min.js',
    './js/bootstrap.min.js',
    './js/jquery.slimscroll.js',
    './js/jquery.scrollTo.min.js',
    './plugins/moment/moment.js',
    './plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    './pages/jquery.form-pickers.init.js',
    './plugins/jstree/jstree.min.js',
    './js/jquery.core.js',
    './js/jquery.app.js',
    './js/archeryosa.js',
    './js/app.js'
];

self.addEventListener('install', async event => {
    const cache = await caches.open('static-meme');
    cache.addAll(staticAssets);
});

self.addEventListener('fetch', event => {
    const {request} = event;
    const url = new URL(request.url);
    if (url.origin === location.origin) {
        event.respondWith(cacheData(request));
    }
    else {
        event.respondWith(networkFirst(request));
    }

});

async function cacheData(request) {
    const cachedResponse = await caches.match(request);
    return cachedResponse || fetch(request);
}

async function networkFirst(request) {
    const cache = await caches.open('dynamic-meme');

    try {
        const response = await fetch(request);
        cache.put(request, response.clone());
        return response;
    }
    catch (error){
        return await cache.match(request);

    }

}