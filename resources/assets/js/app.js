window.plupload = require('plupload');

require('cropper');

Vue.component('vue-progress-bar', require('./components/ProgressBarComponent.vue'));
Vue.component('vue-image-uploader', require('./components/ImageUploaderComponent.vue'));
Vue.component('vue-cropper', require('./components/CropperComponent.vue'));

require('./plugins/tinymce/plugins/images');

require('./package/uploads');
