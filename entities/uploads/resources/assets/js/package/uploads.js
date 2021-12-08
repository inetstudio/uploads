export let uploads = {
  init: () => {
    document.querySelectorAll('.vue-media-field').forEach((element) => {
      new window.Vue({
        el: element,
        components: {
          'media-field': () => import('../components/MediaField.vue')
        }
      });
    });
  }
};
