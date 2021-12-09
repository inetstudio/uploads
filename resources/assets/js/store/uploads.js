window.Admin.vue.stores['uploads'] = new window.Vuex.Store({
    state: {
        src: '',
        crop: {}
    },
    mutations: {
        setSrc (state, src) {
            state.src = src;
        },
        setCrop (state, crop) {
            state.crop = crop;
        },
        setCropData (state, data) {
            state.crop.value = data;
        }
    }
});
