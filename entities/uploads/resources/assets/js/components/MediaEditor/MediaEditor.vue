<template>
  <div id="media_editor_component_modal" class="vue-inmodal">
    <div class="vue-modal-dialog modal-lg">
      <div class="vue-modal-content">
        <div class="vue-modal-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="tabs-container">
                <ul class="nav nav-tabs">
                  <li><a class="nav-link active" data-toggle="tab" href="#properties-tab" v-if="customPropertiesProp.length > 0">Свойства</a></li>
                  <li><a class="nav-link" data-toggle="tab" href="#editor-tab" v-if="isEditableImage">Редактор</a></li>
                </ul>
                <div class="tab-content">
                  <div id="properties-tab" class="tab-pane active" v-if="customPropertiesProp.length > 0">
                    <div class="panel-body">
                      <base-input-text
                          v-for="(item, index) in customPropertiesProp"
                          :key="index"
                          :label="item.title"
                          :name="item.name"
                          :value.sync="mediaItem.custom_properties[item.name]"
                          :showHr="false"
                      />
                    </div>
                  </div>
                  <div id="editor-tab" class="tab-pane" v-if="isEditableImage">
                    <div class="panel-body">
                      <div class="tabs-container">
                        <div class="tabs-left">
                          <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#crops-tab"><i class="fa fa-crop"></i></a></li>
                          </ul>
                          <div class="tab-content ">
                            <div id="crops-tab" class="tab-pane active">
                              <div class="panel-body">
                                <crop-field
                                    :media-item-prop="mediaItemProp"
                                    :file-item-prop="fileItemProp"
                                    :crops-prop="editorProp.crops"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="vue-modal-footer">
          <button type="button" class="btn btn-white" @click.prevent="$emit('close')">Закрыть</button>
          <a href="#" class="btn btn-primary save" @click.prevent="saveMediaItem">Сохранить</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'media-editor',
  components: {
    'crop-field': () => import('./Image/Crop/CropField.vue')
  },
  props: {
    mediaItemProp: {
      type: Object,
      required: true
    },
    editorProp: {
      type: Object,
      default() {
        return {
          crops: []
        };
      }
    },
    customPropertiesProp: {
      type: Array,
      default() {
        return [];
      }
    },
    fileItemProp: {
      type: Object,
      required: true
    },
    save: {
      type: Function,
      default() {
        this.$emit('close');
      }
    }
  },
  data() {
    return this.prepareData();
  },
  computed: {
    isEditableImage() {
      let component = this;

      let regex = new RegExp('^image/[-\\w.]+$');

      return (! _.isEmpty(component.editorProp)) && regex.test(component.fileItemProp.fileType) && component.fileItemProp.fileType !== 'image/gif'
    }
  },
  methods: {
    prepareData() {
      let component = this;

      let mediaItem = _.cloneDeep(component.mediaItemProp);

      component.customPropertiesProp.forEach((item) => {
        if (!_.has(mediaItem.custom_properties, item.name)) {
          mediaItem.custom_properties[item.name] = '';
          item.value = '';
        }
      });

      return {
        mediaItem: mediaItem
      };
    },
    saveMediaItem() {
      let component = this;

      component.save(component.mediaItem);
      component.$emit('close');
    }
  }
};
</script>

<style scoped>

</style>
