<template>
  <div>
    <div class="crop_buttons m-t-lg">
      <a v-for="crop in crops" :key="crop.name" href="#" class="btn m-b-xs m-r-xs btn-w-m" :class="cropButtonClasses(crop)" @click.prevent="startCrop(crop)">{{ crop.title }}</a>
    </div>
    <crop
        :crop-prop="currentCrop"
        :file-item-prop="fileItemProp"
        v-on:update:crop="setCrop"
    />
  </div>
</template>

<script>
export default {
  name: 'crop-field',
  components: {
    'crop': () => import('./Crop.vue')
  },
  props: {
    mediaItemProp: {
      type: Object,
      required: true
    },
    fileItemProp: {
      type: Object,
      required: true
    },
    cropsProp: {
      type: Array,
      default() {
        return [];
      }
    }
  },
  data() {
    return this.prepareData();
  },
  methods: {
    prepareData() {
      let component = this;

      let mediaItem = _.cloneDeep(component.mediaItemProp);
      let crops = _.cloneDeep(component.cropsProp);

      crops.forEach((crop) => {
        let cropValue = _.get(mediaItem.manipulations, crop.name + '.manualCrop', '');

        if (cropValue !== '') {
          let data = cropValue.split(',');

          crop.value = {
            width: parseInt(data[0]),
            height: parseInt(data[1]),
            x: parseInt(data[2]),
            y: parseInt(data[3]),
            rotate: 0,
            scaleX: 1,
            scaleY: 1
          };
        } else {
          crop.value = {
            width: 0,
            height: 0,
            x: 0,
            y: 0,
            rotate: 0,
            scaleX: 1,
            scaleY: 1
          };
        }
      });

      return {
        mediaItem: mediaItem,
        crops: crops,
        currentCrop: crops[0]
      };
    },
    cropButtonClasses(crop) {
      let component = this;

      let classes = [];

      classes.push((crop.value.hasOwnProperty('width') && crop.value.width > 0) ? 'btn-primary' : 'btn-default');
      classes.push((crop.name === component.currentCrop.name) ? 'active' : '');

      return classes;
    },
    startCrop(crop) {
      let component = this;

      component.currentCrop = crop;
    },
    setCrop(payload) {
      let component = this;

      let cropIndex = _.findIndex(component.crops, function(item) {
        return item.name === payload.name;
      });

      if (cropIndex > -1) {
        component.$set(component.crops[cropIndex], 'value', payload.value);
      }
    }
  }
};
</script>

<style scoped>

</style>
